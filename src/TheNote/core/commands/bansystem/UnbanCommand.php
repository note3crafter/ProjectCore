<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\bansystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class UnbanCommand extends Command
{
    private Main $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("unban", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("BanDescription"), "/unban", ["pardon"]);
        $this->setPermission(Permissions::$unban);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }
        if(empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "UnbanUsage"));
            return true;
        }
        $victim = $args[0];
        if (!file_exists($this->plugin->getDataFolder() . "Ban/$victim.yml")) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "UnbanError"));
            return false;
        } else {
            $api->setBan($victim, "bannedby", "");
            $api->setBan($victim, "banreason", "");
            $api->setBan($victim, "banid", "");
            $api->setBan($victim, "bantime", "");
            $api->setBan($victim, "ban", false);
            $sender->sendMessage($api->getCommandPrefix("Ban") . str_replace("{victim}", $victim, $api->getLang($sender->getName(), "UnbanSucces")));
            return true;
        }
    }
}