<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\gamemode;

use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class SpectatorCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("gmspc", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("SpectatorDescription"), "/gmspc", ["spectator", "zuschauer", "gm3"]);
        $this->setPermission(Permissions::$spectator);
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
            if ($sender->hasPermission(Permissions::$spectatorother)) {
                $target = $api->findPlayer($sender, $args[0]);
                if ($target == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "PlayernotOnline"));
                    return false;
                } else {
                    $target->setGamemode(GameMode::SPECTATOR());
                    $target->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($target->getName(), "SpectatorTarget1"));
                    $sender->sendMessage($api->getCommandPrefix("Prefix") . str_replace("{target}", $target->getName(), $api->getLang($sender->getName(),"SpectatorTarget2")));
                    return false;
                }
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"SpectatornoPermTarget"));
                return false;
            }
        }
        $sender->setGamemode(GameMode::SPECTATOR()) ;
        $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"SpectatorSender"));
        return true;
    }
}