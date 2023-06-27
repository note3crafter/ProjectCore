<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\essentials;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class BurnCommand extends Command implements Cancellable
{
    protected $seconds;
    protected $canceled = false;
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("burn", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("BurnDescription"), "/burn");
        $this->setPermission(Permissions::$burn);
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
            return true;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"BurnUsage"));
            return true;
        }
        if (isset($args[0])) {
            $target = $api->findPlayer($sender, $args[0]);
            if ($target == null) {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
                return true;
            }
            $player = $target;
        } else {
            $player = $sender;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"BurnUsage"));
            return false;
        }
        if (!isset($args[1])) {
            $time = 10;
        } elseif (is_numeric($args[0]) >= 0) {
            $time = floor(abs($args[1]));
        } else {
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"BurnSeconds"));
            return true;
        }
        //$ev = new PlayerBurnEvent($player, $sender, $time);
        if ($this->isCancelled()) {
            return true;
        }
        $player->setOnFire(intval($args[1]));
        if ($player === $sender) {
            $cfgmsg = str_replace("{seconds}", $args[1], $api->getLang($sender->getName(),"BurnYourSelf"));
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $cfgmsg);
        } else {
            $stp1 = str_replace("{seconds}", $args[1], $api->getLang($sender->getName(),"BurnMessage"));
            $msg = str_replace("{player}" , $player->getName(), $stp1);
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $msg);
        }
        return true;
    }
    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getSeconds() : int {
        return $this->seconds;
    }

    public function setSeconds(int $seconds) : void{
        $this->seconds = $seconds;
    }
}