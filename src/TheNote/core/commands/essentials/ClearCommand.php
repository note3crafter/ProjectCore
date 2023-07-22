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
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class ClearCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
		$api = new CoreAPI();
        parent::__construct("clear", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("ClearDescription"), "/clear");
        $this->setPermission(Permissions::$clear);
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission(Permissions::$clearother)) {
                $victim = $api->findPlayer($sender, $args[0]);
                if ($victim == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
                    return false;
                } else {
                    $victim->getInventory()->clearAll();
                    $sender->getArmorInventory()->clearAll();
                    $sender->getOffHandInventory()->clearAll();
					$msgplayer = str_replace("{player}" , $sender->getNameTag(), $api->getLang($victim->getName(), "ClearPlayer"));
					$victim->sendMessage($api->getCommandPrefix("Prefix") . $msgplayer);
					$msgvictim = str_replace("{target}" , $victim->getNameTag(), $api->getLang($sender->getName(),"ClearTarget"));
                    $sender->sendMessage($api->getCommandPrefix("Prefix") . $msgvictim);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"ClearError"));
                return false;
            }
        }
        $sender->getInventory()->clearAll();
        $sender->getArmorInventory()->clearAll();
        $sender->getOffHandInventory()->clearAll();
        $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"ClearConfirm"));
        return true;
    }
}
