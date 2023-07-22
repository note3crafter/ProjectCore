<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\other;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\formapi\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\utils\Permissions;

class ServerStatsCommand extends Command
{

    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("serverstats", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("ServerStatsDescription"), "/serverstats", ["sstats"]);
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        $stats = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "stats.json", Config::JSON);
        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    break;
            }
        });
        $form->setTitle($api->getCommandPrefix("UIName"));
        $form->setContent("§0======§f[§dServerStats§f]§0======\n" .
            "§eTotal Joins : \n" .
            "§d" . $stats->get("joins") . "\n" .
            "§eTotal Jumps : \n" .
            "§d" . $stats->get("jumps") . "\n" .
            "§eTotal Kicks : \n" .
            "§d" . $stats->get("kicks") . "\n" .
            "§eTotal Deaths : \n" .
            "§d" . $stats->get("deaths") . "\n" .
            "§eTotal Blocks destroyed : \n" .
            "§d" . $stats->get("break") . "\n" .
            "§eTotal Blocks placed : \n" .
            "§d" . $stats->get("place") . "\n" .
            "§eTotal walking Meters : \n" .
            "§d" . round($stats->get("movewalk")) . "m\n" .
            "§eTotal flying Meters : \n" .
            "§d" . round($stats->get("movefly")) . "m\n" .
            "§eTotal dropped Items : \n" .
            "§d" . $stats->get("drop") . "\n" .
            "§eTotal collected Items : \n" .
            "§d" . $stats->get("pick") . "\n" .
            "§eTotal consumed Items : \n" .
            "§d" . $stats->get("consume") . "\n" .
            "§eTotal Message send : \n" .
            "§d" . $stats->get("messages") . "\n" .
            "§eTotal Restart : \n" .
            "§d" . $stats->get("restarts") . "\n" .
            "§eTotal register Players : \n" .
            "§d" . $stats->get("Users") . "\n" .
            "§eTotal Votes : \n" .
            "§d" . $stats->get("votes"));
        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}