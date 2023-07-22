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
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class GodModeCommand extends Command
{
	private Main $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$api = new CoreAPI();
		parent::__construct("godmode", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("GodModeDescription"), "/godmode", ["god", "gmode"]);
		$this->setPermission(Permissions::$godmode);
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

		if (!isset(Main::$godmod[$sender->getName()])) {
			Main::$godmod[$sender->getName()] = $sender->getName();
			$sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"GodModeOn"));
		} else {
			unset(Main::$godmod[$sender->getName()]);
			$sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"GodModeOff"));
		}
		return true;
	}
}