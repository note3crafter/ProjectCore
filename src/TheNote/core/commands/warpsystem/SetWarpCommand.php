<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\warpsystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class SetWarpCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("setwarp", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("SetWarpDescription"), "/setwarp");
        $this->setPermission(Permissions::$setwarp);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $warp = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "warps.json", Config::JSON);

        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Warp") . $api->getLang($sender->getName(), "SetWarpUsage"));
            return false;
        }

        if (isset($args[0])) {
            $x = $sender->getLocation()->getX();
            $y = $sender->getLocation()->getY();
            $z = $sender->getLocation()->getZ();
            $world = $sender->getWorld()->getFolderName();
            $name = $args[0];
            $warp->set($name, ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world]);
            $warp->save();
            $message = str_replace("{x}", $x, $api->getLang($sender->getName(),"SetWarpSucces"));
            $message1 = str_replace("{y}", $y, $message);
            $message2 = str_replace("{z}", $z, $message1);
            $message3 = str_replace("{world}", $world, $message2);
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $message3);
            return true;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Warp") . $api->getLang($sender->getName(),"SetWarpNumb"));
            return false;
        } else {
            $x = $sender->getLocation()->getX();
            $y = $sender->getLocation()->getY();
            $z = $sender->getLocation()->getZ();
            $world = $sender->getWorld()->getFolderName();
            $name = $args[0];
            $warp->set($name, ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world, "gamemode" => (int)$args[1]]);
            $warp->save();
            $message = str_replace("{x}", $x, $api->getLang($sender->getName(),"SetWarpSuccesGM"));
            $message1 = str_replace("{y}", $y, $message);
            $message2 = str_replace("{z}", $z, $message1);
            $message3 = str_replace("{world}", $world, $message2);
            $message4 = str_replace("{gamemode}", $args[1], $message3);
            $sender->sendMessage($api->getCommandPrefix("Warp") . $message4);
            return true;
        }
    }
}