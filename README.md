# MCPE Citizens2
Second version of my in-dev Citizens remake that uses real entities and not packets.

[![N|Solid](http://i.imgur.com/yanYKEa.png)](https://forums.pmmp.io/)

This PMMP plugin is a WIP port of the Citizens Spigot/Bukkit plugin to run on Pocket Edition servers. Not all planned features are added yet.

# Current features/commands
```
/npc list              - Lists all NPCs
/npc select [id]       - Selects the given NPC
/npc create [name]     - Creates an NPC. Name supports spaces
/npc remove            - Removes selected NPC
/npc rename [new name] - Renames selected NPC
/npc setskin [skin]    - Changes selected NPCs skin
```

Clicking an NPC is detected and sends a temporary message to players. Working on a way to `emit` the interaction to allow other plugins to use it as a hook. Plugin currently supports the latest MCPE and Windows10 edition versions.
