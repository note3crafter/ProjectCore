<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\essentials;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class KickallCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("kickall", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("KickAllDescription"), "/kickall");
        $this->setPermission(Permissions::$kickall);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"KickAllUsage"));
        }
        if (isset($args[0])) {
            $onlinePlayers = $this->plugin->getServer()->getOnlinePlayers();
            foreach ($this->plugin->getServer()->getOnlinePlayers() as $players) {
                if (count($onlinePlayers) === 0 || (count($onlinePlayers) === 1)) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"KickAllNoOnline"));
                } elseif ($players !== $sender) {
                    $message = str_replace("{reason}", $args[0], $api->getLang($sender->getName(),"KickAllSucces"));
                    $players->kick($api->getCommandPrefix("Prefix") . $message, false);
                }
            }
        }
        return true;
    }
}