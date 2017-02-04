<?php

namespace Citizens;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Listener;

class CitizensInteractEvent extends PluginEvent implements Listener {

	public static $handlerList = null;

	protected $npc, $player, $npc_name, $npc_id, $npc_entity_id, $npc_pos, $npc_type, $npc_level_name, $npc_skin;

	public function __construct(Main $plugin, $npc) {
		parent::__construct($plugin, $npc);
		$this->npc            = $npc;
		$this->player         = $npc["player"];
		$this->npc_name       = $npc["npc"]["name"];
		$this->npc_id         = $npc["npc"]["npc_id"];
		$this->npc_entity_id  = $npc["npc"]["entity_id"];
		$this->npc_pos        = $npc["npc"]["pos"];
		$this->npc_type       = $npc["npc"]["npc_type"];
		$this->npc_level_name = $npc["npc"]["level_name"];
		$this->npc_skin       = $npc["npc"]["skin"];
	}

	public function test() {
		return "Hello World";
	}

	public function getNpc() {
		return $this->npc;
	}
	public function getPlayer() {
		return $this->player;
	}
	public function getName() {
		return $this->npc_name;
	}
	public function getNpcId() {
		return $this->npc_id;
	}
	public function getEntityId() {
		return $this->npc_entity_id;
	}
	public function getPos() {
		return $this->npc_pos;
	}
	public function getType() {
		return $this->npc_type;
	}
	public function getLevelName() {
		return $this->npc_level_name;
	}
	public function getSkin() {
		return $this->npc_skin;
	}
}