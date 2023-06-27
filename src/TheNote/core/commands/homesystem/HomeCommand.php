<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\homesystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class HomeCommand extends Command
{
	private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("home", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("HomeDescription"), "/home <home>");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"CommandIngame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Home") . $api->getLang($sender->getName(),"HomeUsage"));
        }
        if (isset($args[0])) {
            $name = $args[0];
            $home = new Config($this->plugin->getDataFolder() . CoreAPI::$homefile . $sender->getName() . ".json", Config::JSON);
            $x = $home->getNested($args[0] . ".X");
            $y = $home->getNested($args[0] . ".Y");
            $z = $home->getNested($args[0] . ".Z");
            $world = $home->getNested($args[0] . ".world");
            if ($name == null) {
                $sender->sendMessage($api->getCommandPrefix("Home") . $api->getLang($sender->getName(),"HomeUsage"));
                return false;
            } else {
                if ($world == null){
                    $message = str_replace("{home}" , $args[0], $api->getLang($sender->getName(),"HomeError"));
                    $sender->sendMessage($api->getCommandPrefix("Error") . $message);
                    return false;
                } else {
                    $this->plugin->getServer()->getWorldManager()->loadWorld($world);
                    $sender->teleport(new Position($x , $y , $z, $this->plugin->getServer()->getWorldManager()->getWorldByName($world)));
                    $message = str_replace("{home}" , $args[0], $api->getLang($sender->getName(),"HomeSucces"));
                    $sender->sendMessage($api->getCommandPrefix("Home") . $message);
                }
                return false;
            }
        }
        return true;
    }
}