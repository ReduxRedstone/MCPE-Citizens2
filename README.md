# MCPE Citizens2
Second version of my in-dev Citizens remake that uses real entities and not packets.

[![N|Solid](http://i.imgur.com/yanYKEa.png)](https://forums.pmmp.io/)

This PMMP plugin is a WIP port of the Citizens Spigot/Bukkit plugin to run on Pocket Edition servers. Not all planned features are added yet.

# Features
- [x] creating
- [x] selecting
- [x] removing
- [x] listing
- [x] renaming
- [x] changing skin
- [ ] changing type [^1]
- [x] moving NPC
- [ ] teleporting to NPC
- [ ] NPC center command
- [ ] NPC look-at function
- [x] click/interact detection
- [x] event emitting for interact

#Usage
```
/npc list              - Lists all NPCs
/npc select [id]       - Selects the given NPC
/npc create [name]     - Creates an NPC. Name supports spaces
/npc remove            - Removes selected NPC
/npc rename [new name] - Renames selected NPC
/npc setskin [skin]    - Changes selected NPCs skin
/npc tphere            - Moves NPC to your current location
/npc here              - Alias of tphere
/npc type [type]       - Changes the entity type. See foot note 1 for more details [^1]
```

#For developers
Citizens emits a custom packet called `CitizensInteractEvent` whenever an NPC is clicked by a player. To hook into this event, for use in other plugins, simply include `Citizens/CitizensInteractEvent` in your plugin while having the Citizens plugin loaded.

###Example
```php
<?php

namespace CitizensHook;

use Citizens\CitizensInteractEvent;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;


class Hook extends PluginBase implements Listener {

    public function onLoad() {
        $this->getLogger()->info("CitizensHook now loaded.");
    }

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getLogger()->info("CitizensHook now enabled.");
    }

    public function onDisable() {
        $this->getLogger()->info("CitizensHook now disabled.");
    }

    public function onCitizensInteractEvent(CitizensInteractEvent $event) {
        $player = $event->getPlayer();
        $player->sendMessage("Bam you clicked an NPC and that hook was tracked by another plugin");
    }
}
```

The event has 9 pieces of data which can be obtained:
```
npc            - obtained via $event->getNpc();       Returns a full array of NPC data
player         - obtained via $event->getPlayer();    Returns a player object of the player who interacted
npc_name       - obtained via $event->getName();      Returns the NPC name
npc_id         - obtained via $event->getNpcId();     Returns the ID of the NPC in thye Citizens plugin
npc_entity_id  - obtained via $event->getEntityId();  Returns the NPC EntityID in the world
npc_pos        - obtained via $event->getPos();       Returns the NPC position
npc_type       - obtained via $event->getType();      Returns the NPC type
npc_level_name - obtained via $event->getLevelName(); Returns the name of the level the NPC was created
npc_skin       - obtained via $event->getSkin();      Returns the NPC skin name
```

#Known bugs
###Duplicated NPCs
~NPCs are removed when the server closes and respawned when the server starts, however it seems when the server crashes or is forced-closed the event does not fire, and does not remove the old NPCs. Thus resulting in "duplicated" NPCs. I have yet to find a fix for this.~ POSSIBLY FIXED


#Foot-notes
[^1]: ^1 Type changing is technically working, however PMMP does not support all entities nor does it allow any entities besides Human to display a name tag. This is a limitation on PMMP.

