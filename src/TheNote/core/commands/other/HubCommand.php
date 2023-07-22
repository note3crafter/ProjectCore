<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\other;

use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\world\sound\EndermanTeleportSound;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class HubCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("hub", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("HubDescription"), "/hub", ["spawn", "lobby"]);
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
        $warp = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "warps.json", Config::JSON);
        $name = "hub";
        $x = $warp->getNested($name . ".X");
        $y = $warp->getNested($name . ".Y");
        $z = $warp->getNested($name . ".Z");
        $world = $warp->getNested($name . ".world");
        if ($name !== null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "HubError"));
            return false;
        } elseif ($world !== null) {
            $this->plugin->getServer()->getWorldManager()->loadWorld($world);
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "HubError"));
            return false;
        } else {
            $sender->teleport(new Position($x, $y, $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(), "HubSucces"));
            if ($api->getConfig("HubFood") === true) {
                $sender->getHungerManager()->setFood(20);
            }
            if ($api->getConfig("HubHeal") === true) {
                $sender->setHealth(20);
            }
            if ($api->getConfig("HubTeleportsound") === true) {
                $sender->getWorld()->addSound($sender->getPosition(), new EndermanTeleportSound());
            }
            return true;
        }
    }
}
