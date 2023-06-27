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

class ReplyCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("reply", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("ReplyDescription"), "/reply <message>", ["r"]);
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $msg = new CoreListner();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("error") . $api->getLang($sender->getName(), "CommandIngame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("info") . $api->getLang($sender->getName(), "ReplyUsage"));
            return false;
        }
        if (!empty($msg->getLastSent($sender->getName()))) {
            $player = $this->plugin->getServer()->getPlayerExact($msg->getLastSent($sender->getName()));
            if ($player instanceof CommandSender) {
                $message = str_replace("{sender}", $sender->getNameTag(), $api->getLang($sender->getName(), "ReplySender"));
                $message1 = str_replace("{player}", $player->getNameTag(), $message);
                $sender->sendMessage($api->getCommandPrefix("MSG") . $message1 . implode(" ", $args));
                $message2 = str_replace("{player}", $sender->getNameTag(), $api->getLang($player->getName(), "ReplyTarget"));
                $player->sendMessage($api->getCommandPrefix("MSG") . $message2 . implode(" ", $args));
                $msg->onMessage($sender, $player);
                Server::getInstance()->getLogger()->info($api->getCommandPrefix("MSG") . $message2 . implode(" ", $args));
                Server::getInstance()->getLogger()->info($api->getCommandPrefix("MSG") . $message1 . implode(" ", $args));
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "PlayernotOnline"));
            }
        } else {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "ReplyNoPlayer"));
        }
        return true;
    }
}