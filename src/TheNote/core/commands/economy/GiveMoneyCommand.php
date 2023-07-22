<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\economy;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\utils\Permissions;

class GiveMoneyCommand extends Command implements Listener
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("givemoney", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("GiveMoneyDescription"), "/givemoney {player} {value}", ["addmoney"]);
        $this->setPermission(Permissions::$givemoney);
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
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Error") .  $api->getLang($sender->getName(),"GiveMoneyUsage"));
            return false;
        }
        if (!isset($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Error") .  $api->getLang($sender->getName(),"GiveMoneyUsage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"GiveMoneyNumb"));
            return false;
        }
        $api->addMoney($target, (int)$args[1]);
        $message = str_replace("{sender}" , $sender->getName(), $api->getLang($target->getName(),"GiveMoneyTarget"));
        $message1 = str_replace("{money}" , $args[1], $message);
        $target->sendMessage($api->getCommandPrefix("Money") . $message1);
        $message2 = str_replace("{victim}" , $target->getName(), $api->getLang($sender->getName(),"GiveMoneySender"));
        $message3 = str_replace("{money}" , $args[1], $message2);
        $sender->sendMessage($api->getCommandPrefix("Money") . $message3);
        return true;
    }
}