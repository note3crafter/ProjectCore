<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\essentials;

use pocketmine\player\Player;
use pocketmine\world\Explosion;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class NukeCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("nuke", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("NukeDescription"), "/nuke");
        $this->setPermission(Permissions::$nuke);
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
            if ($sender->hasPermission(Permissions::$nukeother)) {
                $target = $api->findPlayer($sender, $args[0]);
                if ($target == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
                    return false;
                } else {
                    $explosion = new Explosion($sender->getPosition(),100);
                    $explosion->explodeA();
                    $explosion->explodeB();
                    $target->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($target->getName(),"NukeTarget"));
                    $message = str_replace("{target}", $target->getName(), $api->getLang($sender->getName(),"NukeTarget1"));
                    $sender->sendMessage($api->getCommandPrefix("Prefix") . $message);
                    return false;
                }
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NukeTargetNoPerm"));
                return false;
            }
        }
        $explosion = new Explosion($sender->getPosition(),100);
        $explosion->explodeA();
        $explosion->explodeB();
        $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"NukeSucces"));
        return true;
    }
}