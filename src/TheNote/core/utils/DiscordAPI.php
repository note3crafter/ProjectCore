<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\utils;

use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\task\DiscordTask;

class DiscordAPI
{
    public function backFromAsync($player)
    {
        if ($player === "nolog") {
            return;
        } elseif ($player === "CONSOLE") {
            $player = new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage());
        } else {
            $playerinstance = Server::getInstance()->getPlayerExact($player);
            if ($playerinstance === null) {
                return;
            } else {
                $player = $playerinstance;
            }
        }
    }

    public function sendMessage($player, string $msg): bool
    {
        $dcsettings = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Discord.yml", Config::YAML);
        $name = $dcsettings->get("webhookname");
        $webhook = $dcsettings->get("webhookurl");
        $cleanMsg = $this->cleanMessage($msg);
        $curlopts = [
            "content" => $cleanMsg,
            "username" => $name
        ];
        if ($cleanMsg === "") {
            Server::getInstance()->getLogger()->warning("§cWarning: Empty message cannot be sent to discord.");
            return false;
        }
        Server::getInstance()->getAsyncPool()->submitTask(new DiscordTask($player, $webhook, serialize($curlopts)));
        return true;
    }
    public function cleanMessage(string $msg): string
    {
        $dcsettings = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Discord.yml", Config::YAML);
        $banned = $dcsettings->get("banned_words", []);
        return str_replace($banned, '', $msg);
    }
}