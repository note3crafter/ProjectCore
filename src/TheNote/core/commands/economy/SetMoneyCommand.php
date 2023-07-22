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
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class SetMoneyCommand extends Command implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("setmoney", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("SetMoneyDescription"), "/setmoney {player} {value}");
        $this->setPermission(Permissions::$setmoney);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if(!isset($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"SetMoneyUsage"));
            return false;
        }
        if(!isset($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"SetMoneyUsage"));
            return false;
        }
        if(!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"SetMoneyNumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        $api->setMoney($target, (int)$args[1]);
        $message = str_replace("{target}", $target->getName(), $api->getLang($sender->getName(),"SetMoneySender"));
        $message1 = str_replace("{money}", $args[1] , $message);
        $sender->sendMessage($api->getCommandPrefix("Money") . $message1);
        $message2 = str_replace("{money}", $args[1], $api->getLang($target->getName(),"SetMoneyTarget"));
        $target->sendMessage($api->getCommandPrefix("Money") . $message2);
        return true;
    }

}