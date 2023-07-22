<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\economy;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\listener\ScoreBoardListner;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class PayallCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("payall", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("PayallDescription"), "/payall", ["paya"]);
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
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if (isset($args[0])) {
            if (is_numeric($args[0])) {
                $amount = $args[0];
                $anz = count($this->plugin->getServer()->getOnlinePlayers());
                $tanz = $anz - 1;
                $maxpay = $amount * $tanz;
                $mymoney = $api->getMoney($sender->getName());
                if ($maxpay < $mymoney) {
                    foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                        $api->addMoney($player, $amount);
                        $api->removeMoney($sender, $amount);
                    }
                    $message = str_replace("{name}", $sender->getNameTag(), $api->getLang($sender->getName(), "PayallBC"));
                    $message1 = str_replace("{money}", $args[0], $message);
                    $message2 = str_replace("{amount}", $amount, $message1);
                    $this->plugin->getServer()->broadcastMessage($api->getCommandPrefix("Money") . $message2);
                    if($api->getUser($sender->getName(), "sb") === true){
                        $sb = new ScoreBoardListner();
                        $sb->scoreboard();
                    }
                } else {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PayAllNoMoney"));
                }
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PayAllWrong"));
            }
        } else {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PayALLNoValue"));
        }
        return true;
    }
}
