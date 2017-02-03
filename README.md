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
- [x] click/interact detection
- [ ] event emitting for interact [^2]

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

#Known bugs
###Duplicated NPCs
NPCs are removed when the server closes and respawned when the server starts, however it seems when the server crashes or is forced-closed the event does not fire, and does not remove the old NPCs. Thus resulting in "duplicated" NPCs. I have yet to find a fix for this.


#Foot-notes
[^1]: ^1 Type changing is technically working, however PMMP does not support all entities nor does it allow any entities besides Human to display a name tag. This is a limitation on PMMP.

[^2]: ^2 Clicking an NPC is detected and sends a temporary message to players. Working on a way to `emit` the interaction to allow other plugins to use it as a hook. Plugin currently supports the latest MCPE and Windows10 edition versions.
