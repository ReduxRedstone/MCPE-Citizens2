<?php

namespace Citizens;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

use pocketmine\Player;

use pocketmine\entity\Human;

use pocketmine\level\Location;


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
                            $sender->sendMessage("§6§lCitizens §r§6> §r§aRemoved NPC §r".$this->plugin->npcs[$id]["name"].".");
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

                        case 'rename':
                            if (!isset($args[1])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c No name set!§r");
                                return false;
                            }
                            if (!isset($this->plugin->selections[$sender->getName()])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c You must select an NPC!§r");
                                return false;
                            }
                            $id = $this->plugin->selections[$sender->getName()];
                            if (!isset($this->plugin->npcs[$id])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Invalid NPC selection!§r");
                                return false;
                            }

                            unset($args[0]);
                            $name = implode(" ", $args);

                            $sender->sendMessage("§6§lCitizens §r§6> §r§aRenamed NPC §r".$this->plugin->npcs[$id]["name"]."§a to§r ".$name.".");
                            $this->plugin->renameNPC($name, $id);
                            break;

                        case 'setskin':
                            if (!isset($args[1])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c No skin set!§r");
                                return false;
                            }
                            if (!isset($this->plugin->selections[$sender->getName()])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c You must select an NPC!§r");
                                return false;
                            }
                            $id = $this->plugin->selections[$sender->getName()];
                            if (!isset($this->plugin->npcs[$id])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Invalid NPC selection!§r");
                                return false;
                            }

                            $sender->sendMessage("§6§lCitizens §r§6> §r§aReskinned NPC §r".$this->plugin->npcs[$id]["name"].".");
                            $this->plugin->setSkin($args[1], $id);
                            break;

                        case 'tp':
                            if (!isset($this->plugin->selections[$sender->getName()])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c You must select an NPC!§r");
                                return false;
                            }
                            $id = $this->plugin->selections[$sender->getName()];
                            if (!isset($this->plugin->npcs[$id])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Invalid NPC selection!§r");
                                return false;
                            }
                            $this->plugin->goToNPC($sender, $id);
                            break;

                        case 'here':
                        case 'tphere':
                            if (!isset($this->plugin->selections[$sender->getName()])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c You must select an NPC!§r");
                                return false;
                            }
                            $id = $this->plugin->selections[$sender->getName()];
                            if (!isset($this->plugin->npcs[$id])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Invalid NPC selection!§r");
                                return false;
                            }
                            $this->plugin->moveNPC(new Location($sender->x,$sender->y,$sender->z,$sender->yaw,$sender->pitch), $id, $sender);
                            break;

                        case 'type':
                            if (!isset($args[1])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Select a type!§r");
                                return false;
                            }
                            if (!isset($this->plugin->selections[$sender->getName()])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c You must select an NPC!§r");
                                return false;
                            }
                            $id = $this->plugin->selections[$sender->getName()];
                            if (!isset($this->plugin->npcs[$id])) {
                                $sender->sendMessage("§4§l[ERROR]§r§c Invalid NPC selection!§r");
                                return false;
                            }
                            $this->plugin->setType($args[1], $id, $sender);
                            break;

                        case 'list':
                            $list = "§a==========§6§lCitizens§r§a==========§r";
                            foreach ($this->plugin->npcs as $npc) {
                                $name = $npc["name"];
                                $id = $npc["npc_id"];
                                $x = round($npc["pos"]["x"], 3);
                                $y = round($npc["pos"]["y"], 3);
                                $z = round($npc["pos"]["z"], 3);
                                $list .= "\n§r".$name."§r§b ID: §9".$id."§r§b Pos: §a".$x.", ".$y.", ".$z."";
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