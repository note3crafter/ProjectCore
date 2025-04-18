<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\utils;

class Permissions
{
    public static $defaultperm = "ProjectCore";

    //BanSystem
    public static $ban = "core.command.ban";
    public static $unban = "core.command.unban";

    //EconomySystem
    public static $givemoney = "core.command.givemoney";
    public static $setmoney = "core.command.setmoney";
    public static $takemoney = "core.command.takemoney";

    //Essentials
    public static $back = "core.command.back";
    public static $burn = "core.command.burn";
    public static $chatclear = "core.command.chatclear";
    public static $clear = "core.command.clear";
    public static $clearother = "core.command.clear.other";
    public static $day = "core.command.day";
    public static $enderchest = "core.command.enderchest";
    public static $extinguish = "core.command.extinguish";
    public static $extinguishother = "core.command.extinguish.other";
    public static $feed = "core.command.feed";
    public static $feedother = "core.command.feed.other";
    public static $fly = "core.command.fly";
    public static $flyother = "core.command.fly.other";
    public static $godmode = "core.command.godmode";
    public static $heal = "core.command.heal";
    public static $healother = "core.command.heal.other";
    public static $id = "core.command.id";
    public static $kickall = "core.command.kickall";
    public static $kick = "core.command.kick";
    public static $milk = "core.command.milk";
    public static $milkother = "core.command.milk.other";
    public static $near = "core.command.near";
    public static $nick = "core.command.nick";
    public static $night = "core.command.night";
    public static $nuke = "core.command.nuke";
    public static $nukeother = "core.command.nuke.other";
    public static $position = "core.command.position";
    public static $realname = "core.command.realname";
    public static $rename = "core.command.rename";
    public static $repair = "core.command.repair";
    public static $sign = "core.command.sign";
    public static $size = "core.command.size";
    public static $speed = "core.command.speed";
    public static $sudo = "core.command.sudo";
    public static $top = "core.command.top";
    public static $tpall = "core.command.tpall";
    public static $vanish = "core.command.vanish";

    //Events
    public static $blockbreak = "core.events.blockbreak";
    public static $blockplace = "core.events.blockplace";
    public static $interactchest = "core.events.interact.chest";
    public static $interactdoors = "core.events.interact.doors";
    public static $interactdragonegg = "core.events.interact.dragonegg";
    public static $interactflintsteel = "core.events.interact.flintsteel";
    public static $interacthoneycomb = "core.events.interact.honeycomb";
    public static $interactitemframe = "core.events.interact.itemframe";
    public static $interactsign = "core.events.interact.sign";
    public static $interactbonemeal = "core.events.interact.bonemeal";
    public static $interactbucket = "core.events.interact.buckets";
    public static $itemuseicebomb = "core.events.itemuse.icebomb";


    //Gamemode
    public static $adventure = "core.command.adventure";
    public static $adventureother = "core.command.acventure.other";
    public static $creative = "core.command.creative";
    public static $creativeother = "core.command.creative.other";
    public static $spectator = "core.command.spectator";
    public static $spectatorother = "core.command.spectator.other";
    public static $survival = "core.command.survival";
    public static $survivalother = "core.command.survival.other";

    //GroupSystem
    public static $group = "core.command.group";

    //Misc
    public static $adminitems = "core.command.adminitems";
    public static $adminitemssuperbow = "core.command.admintitems.superbow";
    public static $adminitemsexplosivbow = "core.command.admintitems.explosivbow";
    public static $adminitemssexplosivegg = "core.command.admintitems.explosivegg";
    public static $lightning = "core.command.lightning";

    //MSGSystem
    public static $nodmbypass = "core.command.nodm.bypass";

    //Other
    public static $clearlagg = "core.command.clearlagg";
    public static $seeperms = "core.command.seeperms";
    public static $sethub = "core.command.sethub";

    //WarpSystem
    public static $delwarp = "core.command.delwarp";
    public static $setwarp = "core.command.setwarp";

    //WeatherSystem
    public static $weather = "core.command.weather";

    //SpawnProtection
    public static $spawnprotectionbypass = "core.events.spawnprotection.bypass";

}