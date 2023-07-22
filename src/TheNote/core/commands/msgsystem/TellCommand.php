<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\msgsystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use TheNote\core\CoreAPI;
use TheNote\core\listener\CoreListner;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class TellCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("tell", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("TellDescription"), "/tell <Spieler> <Nachrricht>", ["msg", "whisper", "w"]);
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $msg = new CoreListner();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"TellUsage"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        unset($args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("MSG") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        if ($target === $sender) {
            $sender->sendMessage($api->getCommandPrefix("MSG") . $api->getLang($sender->getName(),"TellNotYou"));
            return false;
        }
        if ($api->getUser($target->getName(), "nodm") === true) {
            if (!$sender->hasPermission(Permissions::$nodmbypass)) {
                $sender->sendMessage($api->getCommandPrefix("MSG") . $api->getLang($sender->getName(), "TellMSGBlock"));
                return false;
            } elseif ($target instanceof Player) {
                $message = str_replace("{sender}", $sender->getNameTag(), $api->getLang($sender->getName(), "TellSender"));
                $message1 = str_replace("{player}", $target->getNameTag(), $message);
                $sender->sendMessage($api->getCommandPrefix("MSG") . $message1 . implode(" ", $args));
                $message2 = str_replace("{player}", $sender->getNameTag(), $api->getLang($target->getName(), "TellTarget"));
                $target->sendMessage($api->getCommandPrefix("MSG") . $message2 . implode(" ", $args));
                $msg->onMessage($sender, $target);
                Server::getInstance()->getLogger()->info($api->getCommandPrefix("msg") . $message2 . implode(" ", $args));
                Server::getInstance()->getLogger()->info($api->getCommandPrefix("msg") . $message1 . implode(" ", $args));
                return true;
            }
        } elseif ($target instanceof Player) {
            $message = str_replace("{sender}", $sender->getNameTag(), $api->getLang($sender->getName(), "TellSender"));
            $message1 = str_replace("{player}", $target->getNameTag(), $message);
            $sender->sendMessage($api->getCommandPrefix("MSG") . $message1 . implode(" ", $args));
            $message2 = str_replace("{player}", $sender->getNameTag(), $api->getLang($target->getName(), "TellTarget"));
            $target->sendMessage($api->getCommandPrefix("MSG") . $message2 . implode(" ", $args));
            $msg->onMessage($sender, $target);
            Server::getInstance()->getLogger()->info($api->getCommandPrefix("msg") . $message2 . implode(" ", $args));
            Server::getInstance()->getLogger()->info($api->getCommandPrefix("msg") . $message1 . implode(" ", $args));
        }
        return true;
    }
}