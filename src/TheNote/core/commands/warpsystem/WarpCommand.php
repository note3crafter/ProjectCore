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
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class WarpCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("warp", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("WarpDescription"), "/warp");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"commandingame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Warp") . $api->getLang($sender->getName(),"WarpUsage"));
            return false;
        }
        if (isset($args[0])) {
            $warp = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "warps.json", Config::JSON);
            $x = $warp->getNested($args[0] . ".X");
            $y = $warp->getNested($args[0] . ".Y");
            $z = $warp->getNested($args[0] . ".Z");
            $world = $warp->getNested($args[0] . ".world");
            $gamemode = $warp->getNested($args[0] . ".gamemode");
            if ($gamemode === null) {
                $sender->setGamemode(GameMode::SURVIVAL());
            } elseif ($gamemode === 0) {
                $sender->setGamemode(GameMode::SURVIVAL());
            } elseif ($gamemode === 1) {
                $sender->setGamemode(GameMode::CREATIVE());
            } elseif ($gamemode === 2) {
                $sender->setGamemode(GameMode::ADVENTURE());
            } elseif ($gamemode === 3) {
                $sender->setGamemode(GameMode::SPECTATOR());
            }
            if ($world == null) {
                $message = str_replace("{warp}", $args[0], $api->getLang($sender->getName(), "WarpNotExist"));
                $sender->sendMessage($api->getCommandPrefix("Warp") . $message);
                return false;
            } else {
                $this->plugin->getServer()->getWorldManager()->loadWorld($world);
                $sender->teleport(new Position($x, $y, $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
                $message = str_replace("{warp}", $args[0], $api->getLang($sender->getName(), "WarpSuccces"));
                $sender->sendMessage($api->getCommandPrefix("Warp") . $message);
            }
            return true;
        }
        return true;
    }
}