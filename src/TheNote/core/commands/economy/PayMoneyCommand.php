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

class PayMoneyCommand extends Command implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("pay", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("PayMoneyDescription"), "/pay {player} {value}");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"CommandIngame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"PayMoneyUsage"));
            return false;
        }
        if (empty($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"PayMoneyUsage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"PayMoneyNumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        if ($sender->getName() == $api->findPlayer($sender, $args[0])){
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"PayMoneyYourself"));
            return false;
        }
        if ($args[1] > $api->getMoney($sender->getName())) {
            $sender->sendMessage($api->getCommandPrefix("Money") . $api->getLang($sender->getName(),"PayMoneynoMoney"));
            return false;
        }
        $api->addMoney($target, (int)$args[1]);
        $api->removeMoney($sender, (int)$args[1]);
        $message = str_replace("{victim}", $target->getName(), $api->getLang($target->getName(),"PayMoneyTarget"));
        $message1 = str_replace("{money}", $args[1] , $message);
        $sender->sendMessage($api->getCommandPrefix("Money") . $message1);
        $message2 = str_replace("{player}", $sender->getName(), $api->getLang($sender->getName(), "PayMoneySender"));
        $message3 = str_replace("{money}", $args[1] , $message2);
        $target->sendMessage($api->getCommandPrefix("Money") . $message3);
        return true;
    }
}