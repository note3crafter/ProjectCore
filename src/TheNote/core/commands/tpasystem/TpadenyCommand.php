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

class TpadenyCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("tpadeny", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("TPAdenyDescription"), "/tpadeny");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"TPADenyUsage"));
        } else {
            $this->tpar($sender->getName());
        }
        return true;
    }
    public function tpar(string $name) : void
    {
        $api = new CoreAPI();
        $tpa = new CoreListner();
        $player = $this->plugin->getServer()->getPlayerExact($name);
        if($tpa->getInviteControl($name)){
            $sender = $this->plugin->getServer()->getPlayerExact($tpa->getInvite($name));
            unset($tpa->invite[$name]);
            $message = str_replace("{player}", $name, $api->getLang($name, "TPADenySender"));
            $sender->sendMessage($api->getCommandPrefix("TPA") . $message);
        } else {
            $player->sendMessage($api->getCommandPrefix("TPA") . $api->getLang($name,"TPADenyTarget"));
        }
    }
}