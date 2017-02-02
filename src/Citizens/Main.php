<?php

namespace Citizens;

use Citizens\Commands;
use Citizens\Config;

use pocketmine\plugin\PluginBase;

use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerKickEvent;

use pocketmine\level\Location;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\StringTag;

use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\RemoveEntityPacket;
use pocketmine\network\protocol\InteractPacket;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;

use pocketmine\item\Item;
use pocketmine\utils\UUID;

use pocketmine\Player;


class Main extends PluginBase implements Listener {

    public $config, $npcs = array(), $selections = array();

    public function onLoad() {
        $this->getLogger()->info("Citizens by Redux now loaded.");
    }

    public function onEnable() {
        $this->config = new Config($this);
        $this->config->load();

        $this->loadNPCs();

        $this->getCommand("npc")->setExecutor(new Commands($this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getLogger()->info("Citizens by Redux now enabled.");
    }

    public function onDisable() {
        foreach ($this->npcs as $npc) {
            $this->despawnNPC($npc["npc_id"]);
        }
        $this->getLogger()->info("Citizens by Redux now disabled.");
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
    }

    public function onPacketReceived(DataPacketReceiveEvent $event) {
        // Detects if a player clicked an NPC. Working on an emit system so plugins can hook into this click
        $packet = $event->getPacket();
        $player = $event->getPlayer();
        if (isset($packet->action)) {
            $action = $packet->action;
        }
        if ($packet instanceof InteractPacket and isset($action) and $action === InteractPacket::ACTION_LEFT_CLICK) {
            $entityID = $packet->target;
            $entity = $player->getLevel()->getEntity($entityID);
            $entityUUID = (string)$entity->getUniqueId();

            if (isset($this->npcs[$entityUUID])) {
                $event->setCancelled(true);
                $player->sendMessage("Clicked NPC ".$this->npcs[$entityUUID]["name"]);
            }
        }
    }

    public function loadNPCs() {
        $this->npcs = json_decode(file_get_contents("./plugins/Citizens/npcs/_all.json"), true);
        if (empty($this->npcs)) return;
        foreach ($this->npcs as $npc) {
            $this->getLogger()->info(print_r($npc, true));
            $this->spawnNPC($npc);
        }
    }

    public function createNPC($player, $name) {

        $pos = new Location($player->x,$player->y,$player->z,$player->yaw,$player->pitch);
        if (empty($this->npcs)) {
            $id = 0;
        } else {
            end($this->npcs);
            $id = key($this->npcs);
            $id++;
        }

        $data = array(
                    "npc_id"=>$id,
                    "level_name"=>$player->getLevel()->getName(),
                    "name"=>$name,
                    "skin"=>"default",
                    "pos"=>(array)$pos
                    );
        $this->npcs[$id] = $data;

        $json = fopen("./plugins/Citizens/npcs/_all.json", "wb");
        fwrite($json, json_encode($this->npcs));
        fclose($json);

        $this->spawnNPC($data);
    }

    public function spawnNPC($data) {

        $level = $this->getServer()->getLevelByName($data["level_name"]);

        $pos = new Location($data["pos"]["x"],$data["pos"]["y"],$data["pos"]["z"],$data["pos"]["yaw"],$data["pos"]["pitch"]);

        $x = $data["pos"]["x"] >> 4;
        $z = $data["pos"]["z"] >> 4;

        $level->loadChunk($x, $z);
        $chunk = $level->getChunk($x, $z);

        $nbt = new CompoundTag("", [
                    new ListTag("Pos", [
                        new DoubleTag(0, $pos->x),
                        new DoubleTag(1, $pos->y),
                        new DoubleTag(2, $pos->z)
                    ]),
                    new ListTag("Motion", [
                        new DoubleTag(0, 0.0),
                        new DoubleTag(1, 0.0),
                        new DoubleTag(2, 0.0)
                    ]),
                    new ListTag("Rotation", [
                        new FloatTag(0, $pos->yaw),
                        new FloatTag(1, $pos->pitch)
                    ]),
                    new ByteTag("Invulnerable", 1),
                    new StringTag("NameTag", $data["name"]),
                ]);
        $nbt->Pos->setTagType(NBT::TAG_Double);
        $nbt->Motion->setTagType(NBT::TAG_Double);
        $nbt->Rotation->setTagType(NBT::TAG_Float);

        $skin = $this->getSkin($data["skin"]);

        $human = Entity::createEntity("Human", $chunk, $nbt);
        $human->setSkin($skin, 'Standard_Custom');
        $human->setNameTagVisible(true);
        $human->setNameTagAlwaysVisible(true);

        $human->spawnToAll();

        $count = count($this->getServer()->getLevelByName($data["level_name"])->getEntities());
        $this->npcs[$data["npc_id"]]["entity_id"] = $count++;
    }

    public function despawnNPC($id) {
        if (empty($this->npcs)) {
            $player->sendMessage("§4§l[ERROR]§r§c No NPCs stored!§r");
            return;
        }
        if (!isset($this->npcs[$id])) {
            $player->sendMessage("§4§l[ERROR]§r§c You must enter a valid NPC ID!§r");
            return;
        }

        $entity = $this->getServer()->getLevelByName($this->npcs[$id]["level_name"])->getEntity($this->npcs[$id]["entity_id"]);
        if ($entity instanceof Human) {
            $this->getServer()->getLevelByName($this->npcs[$id]["level_name"])->removeEntity($entity);
            unset($this->npcs[$id]);
        }
    }

    public function removeNPC($id) {

        $this->despawnNPC($id);

        $json = fopen("./plugins/Citizens/npcs/_all.json", "wb");
        fwrite($json, json_encode($this->npcs));
        fclose($json);
    }

    public function selectNPC($player, $id) {
        if (!isset($this->npcs[$id])) {
            $player->sendMessage("§4§l[ERROR]§r§c You must enter a valid NPC ID!§r");
            if (isset($this->selections[$player->getName()])) unset($this->selections[$player->getName()]);
            return false;
        }
        $this->selections[$player->getName()] = $id;
        $player->sendMessage("§6§lCitizens §r§6> §r§aSelected §b".$this->npcs[$id]["name"]."§a ID §9".$this->npcs[$id]["npc_id"].".");
    }

    public function getSkin($skin) {
        $path = './plugins/Citizens/skins/'.$skin.'.png';
        if (!file_exists($path) && !is_dir($path)) {
            $path = './plugins/Citizens/skins/default.png';
        }
        $img = imagecreatefrompng($path);
        $bytes = '';
        $l = (int)getimagesize($path)[1];

        for ($y = 0; $y < $l; $y++) {
            for ($x = 0; $x < 64; $x++) {
                $rgba = imagecolorat($img, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $bytes .= chr($r).chr($g).chr($b).chr($a);
            }
        }

        imagedestroy($img);

        return $bytes;
    }
}