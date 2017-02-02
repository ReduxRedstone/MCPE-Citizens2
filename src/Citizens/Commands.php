<?php

namespace Citizens;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\Player;
use pocketmine\entity\Human;


class Commands implements CommandExecutor {

    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        switch($command->getName()) {
            case "npc":
                if ($sender instanceof Player) {
                    if (!isset($args[0])) {
                        $sender->sendMessage("§4§l[ERROR]§r§c You must enter an NPC command!§r");
                        return false;
                    }
                    switch ($args[0]) {
                        case 'create':
                            if (!isset($args[1])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c You must enter an NPC name!§r");
                                return false;
                            }
                            unset($args[0]);
                            $name = implode(" ", $args);
                            $this->plugin->createNPC($sender, $name);
                            break;
                        case 'remove':
                            if (!isset($this->plugin->selections[$sender->getName()])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c You must select an NPC!§r");
                                return false;
                            }
                            $id = $this->plugin->selections[$sender->getName()];
                            if (!isset($this->plugin->npcs[$id])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Invalid NPC selection!§r");
                                return false;
                            }
                            $this->plugin->removeNPC($id);
                            break;
                        case 'select':
                            if (!isset($args[1])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c No ID set!§r");
                                if (isset($this->plugin->selections[$sender->getName()])) unset($this->plugin->selections[$sender->getName()]);
                                return false;
                            }
                            $id = (int)$args[1];
                            if (!is_int($id)) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Invalid NPC ID!§r");
                                if (isset($this->plugin->selections[$sender->getName()])) unset($this->plugin->selections[$sender->getName()]);
                                return false;
                            }
                            $this->plugin->selectNPC($sender, $id);
                            break;
                        case 'list':
                            $list = "§a==========§6§lCitizens§r§a==========§r";
                            foreach ($this->plugin->npcs as $npc) {
                                $name = $npc["name"];
                                $id = $npc["npc_id"];
                                $x = round($npc["pos"]["x"], 3);
                                $y = round($npc["pos"]["y"], 3);
                                $z = round($npc["pos"]["z"], 3);
                                $list .= "\n§b".$name."§r§b ID: §9".$id."§r§b Pos: §a".$x.", ".$y.", ".$z."";
                            }
                            $list .= "\n§a============================§r";
                            $sender->sendMessage($list);
                            break;
                    }
                    return true;
                }
                return false;
        }
    }
}