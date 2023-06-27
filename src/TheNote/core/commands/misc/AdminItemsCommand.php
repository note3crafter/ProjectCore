<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\misc;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class AdminItemsCommand extends Command implements Listener
{

    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("adminitems", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("AdminItemsDescription"), "/adminitems" , ["ai", "aitmes"]);
        $this->setPermission(Permissions::$adminitems);
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args):bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"nopermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"AdminItemsUsage"));
            return true;
        }
        if ($args[0] == "superbow") {
            if ($sender->hasPermission(Permissions::$adminitemssuperbow)) {
                $this->superbow($sender);
                $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"AdminItemSucces"));
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"AdminItemError"));
            }
        }
        if ($args[0] == "explosivbow") {
            if ($sender->hasPermission(Permissions::$adminitemsexplosivbow)) {
                $this->explosivbow($sender);
                $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"AdminItemSucces"));
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"AdminItemError"));
            }
        }
        if ($args[0] == "explosivegg") {
            if ($sender->hasPermission(Permissions::$adminitemssexplosivegg)) {
                $this->explodeegg($sender);
                $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"AdminItemSucces"));
            } else {
                $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"AdminItemError"));
            }
        }
        return true;
    }
    public function superbow(Player $player)
    {
        $sbogen = VanillaItems::BOW();
        $sbogen->setCustomName("§f[§cSuperBow§f]");
        $sbogen->getNamedTag()->setString("custom_data", "super_bow");
        $player->getInventory()->addItem($sbogen);

    }
    public function explosivbow(Player $player)
    {
        $ebow = VanillaItems::BOW();
        $ebow->setCustomName("§f[§cExplosivBow§f]");
        $ebow->getNamedTag()->setString("custom_data", "explode_bow");
        $player->getInventory()->addItem($ebow);

    }
    public function explodeegg(Player $player)
    {
        $egg = VanillaItems::EGG();
        $egg->setCustomName("§f[§cExplosivEgg§f]");
        $egg->getNamedTag()->setString("custom_data", "explode_egg");
        $player->getInventory()->addItem($egg);
    }
}