<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\essentials;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class FlyCommand extends Command implements Listener
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("fly", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("FlyDescription"), "/fly");
        $this->setPermission(Permissions::$fly);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission(Permissions::$flyother)) {
                $victim = $api->findPlayer($sender, $args[0]);
                if ($victim == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
                    return false;
                } elseif ($victim->getAllowFlight() === true) {
                    $victim->setAllowFlight(false);
                    $victim->setFlying(false);
                    $message1 = str_replace("{sender}" , $sender->getNameTag(), $api->getLang($victim->getName(),"FlyTargetOff"));
                    $victim->sendMessage($api->getCommandPrefix("Prefix") . $message1);
                    $message = str_replace("{target}" , $victim->getName(), $api->getLang($sender->getName(),"FlyTargetOff1"));
                    $sender->sendMessage($api->getCommandPrefix("Prefix") . $message);
                } else {
                    $victim->setAllowFlight(true);
                    $victim->setFlying(true);
                    $message1 = str_replace("{sender}" , $sender->getNameTag(), $api->getLang($victim->getName(),"FlyTargetOn"));
                    $victim->sendMessage($api->getCommandPrefix("Prefix") . $message1);
                    $message = str_replace("{target}" , $victim->getName(), $api->getLang($sender->getName(),"FlyTargetOn1"));
                    $sender->sendMessage($api->getCommandPrefix("Prefix") . $message);
                }
                return false;
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"FlyTargetNoPerm"));
                return false;
            }
        }
        if ($sender->getAllowFlight() === true) {
            $sender->setAllowFlight(false);
            $sender->setFlying(false);
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"FlyOff"));
        } else {
            $sender->setAllowFlight(true);
            $sender->setFlying(true);
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"FlyOn"));
        }
        return false;
    }
}