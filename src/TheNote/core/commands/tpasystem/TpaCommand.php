<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\tpasystem;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\listener\CoreListner;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class TpaCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("tpa", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("TPADescription"), "/tpa");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $tpa = new CoreListner();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"TPAUsage"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target === $sender){
            $sender->sendMessage($api->getCommandPrefix("TPA") . $api->getLang($sender->getName(),"TPANotYourSelf"));
            return false;
        }
        if ($target instanceof Player) {
            $tpa->setInvite($sender, $target);
            $message = str_replace("{sender}", $sender->getName(), $api->getLang($target->getName(),"TPATarget"));
            $target->sendMessage($api->getCommandPrefix("TPA") . $message);
            $message1 = str_replace("{target}", $target->getName(), $api->getLang($sender->getName(),"TPASender"));
            $sender->sendMessage($api->getCommandPrefix("TPA") . $message1);
        } else {
            $sender->sendMessage($api->getCommandPrefix("TPA") . $api->getLang($sender->getName(),"PlayernotOnline"));
        }
        return true;
    }
}


