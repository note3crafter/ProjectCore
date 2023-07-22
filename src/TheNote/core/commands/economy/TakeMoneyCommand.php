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
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class TakeMoneyCommand extends Command implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("takemoney", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("TakeMoneyDescription"), "/takemoney {player} {value}");
        $this->setPermission(Permissions::$takemoney);
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
        if (!isset($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"TakeMoneyUsage"));
            return false;
        }
        if (!isset($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"TakeMoneyUsage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"TakeMoneyNumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if($target === $sender){
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"TakeMoneynotYourself"));
            return false;
        }

        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        $api->removeMoney($target, (int)$args[1]);
        $message = str_replace("{target}", $target->getName(), $api->getLang($sender->getName(),"TakeMoneySender"));
        $message1 = str_replace("{money}", $args[1] , $message);
        $sender->sendMessage($api->getCommandPrefix("Money") . $message1);
        $message2 = str_replace("{money}", $args[1], $api->getLang($target->getName(), "TakeMoneyTarget"));
        $message3 = str_replace("{sender}", $sender->getName() , $message2);
        $target->sendMessage($api->getCommandPrefix("Money") . $message3);
        return true;
    }
}
