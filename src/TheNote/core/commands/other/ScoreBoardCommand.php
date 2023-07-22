<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\other;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\listener\ScoreBoardListner;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class ScoreBoardCommand extends Command
{

    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("sb", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("ScoreBoardDescription"), "/sb", ["scoreboard"]);
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "ScoreBoardUsage"));
            return false;
        }
        if ($args[0] == "off") {
            $pk = new RemoveObjectivePacket();
            $pk->objectiveName = "standart";
            $sender->getNetworkSession()->sendDataPacket($pk);
            $api->setUser($sender, "sb", false);
            $sender->sendMessage($api->getCommandPrefix("Prefix"). $api->getLang($sender->getName(), "ScoreBoardOff"));
        }
        if ($args[0] == "on") {
            $api->setUser($sender, "sb", true);
            if($api->getUser($sender->getName(), "sb") === true){
                $sb = new ScoreBoardListner();
                $sb->scoreboard();
            }            $sender->sendMessage($api->getCommandPrefix("Prefix"). $api->getLang($sender->getName(), "ScoreBoardOn"));
        }
        return true;
    }
}