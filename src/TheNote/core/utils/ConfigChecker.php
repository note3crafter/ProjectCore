<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\utils;

use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class ConfigChecker
{
    public function cfgcheck(): void
    {
        $api = new CoreAPI();
        if ($api->getConfig("Version") !== Main::$version) {
            rename(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Config.yml", Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Config_OLD.yml");
            Main::getInstance()->saveResource("Settings/Config.yml", true);
            Server::getInstance()->getLogger()->info("Config.yml are outdated! The old Config file are renamed to Config_OLD.yml");
        }
        if ($api->getCommandPrefix("Version") !== Main::$version) {
            rename(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "LangCommandPrefix.yml", Main::getInstance()->getDataFolder() . CoreAPI::$settings . "LangCommandPrefix_OLD.yml");
            Main::getInstance()->saveResource("Lang/Config.yml", true);
            Server::getInstance()->getLogger()->info("Config.yml are outdated! The old Config file are renamed to Config_OLD.yml");
        }
        if ($api->modules("Version") !== Main::$version) {
            rename(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Modules.yml", Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Modules_OLD.yml");
            Main::getInstance()->saveResource("Settings/Modules.yml", true);
            Server::getInstance()->getLogger()->info("Modules.yml are outdated! The old Config file are renamed to Modules_OLD.yml");
        }
        $deu = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$lang . "LangDEU.json");
        if ($deu->get("Version")!== Main::$version) {
            Main::getInstance()->info("Language Files are outdated Please delete the Files to create new ones!");
        }
    }
}