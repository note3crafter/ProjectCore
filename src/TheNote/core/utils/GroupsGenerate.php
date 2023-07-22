<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\utils;

use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class GroupsGenerate
{
    public function groupsgenerate(): void
    {
        if (!file_exists(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "groups.yml")) {
            $groups = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "groups.yml", Config::YAML);
            $groups->set("DefaultGroup", "player");

            $groups->setNested("Groups.player.groupprefix", "§f[§ePlayer§f]§7");
            $groups->setNested("Groups.player.format1", "§f[§ePlayer§f] §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.player.format2", "§f[§ePlayer§f] {clan} §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.player.format3", "§f[§ePlayer§f] {heirat} §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.player.format4", "§f[§ePlayer§f] {heirat} {clan} §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.player.nametag", "§f[§ePlayer§f] §7{name}");
            $groups->setNested("Groups.player.displayname", "§eP§f:§7{name}");
            $groups->setNested("Groups.player.permissions", [Permissions::$defaultperm]);

            $groups->setNested("Groups.premium.groupprefix", "§f[§6Premium§f]§6");
            $groups->setNested("Groups.premium.format1", "§f[§6Premium§f] §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.format2", "§f[§6Premium§f] {clan} §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.format3", "§f[§6Premium§f] {heirat} §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.format4", "§f[§6Premium§f] {heirat} {clan} §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.nametag", "§f[§6Premium§f] §6{name}");
            $groups->setNested("Groups.premium.displayname", "§6P§f:§6{name}");
            $groups->setNested("Groups.premium.permissions", [Permissions::$defaultperm]);

            $groups->setNested("Groups.owner.groupprefix", "§f[§4Owner§f]§c");
            $groups->setNested("Groups.owner.format1", "§f[§4Owner§f] §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.format2", "§f[§4Owner§f] {clan} §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.format3", "§f[§4Owner§f] {heirat} §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.format4", "§f[§4Owner§f] {heirat} {clan} §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.nametag", "§f[§4Owner§f] §c{name}");
            $groups->setNested("Groups.owner.displayname", "§4O§f:§c{name}");
            $groups->setNested("Groups.owner.permissions", [Permissions::$defaultperm]);

            $groups->save();
        }
    }

}