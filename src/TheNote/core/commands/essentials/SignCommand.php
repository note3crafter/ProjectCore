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
use pocketmine\utils\TextFormat;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class SignCommand extends Command {

    private Main $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
		parent::__construct("sign", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("SignDescription"), "/sign <text>");
		$this->setPermission(Permissions::$sign);
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
		if(empty($args)) {
			$sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"SignUsage"));
			return false;
		}
		$item = $sender->getInventory()->getItemInHand();
        $date = date("d.m.Y");
        $time = date("H:i:s");
        $name = $sender->getName();
        $fullargs = implode(" ", $args);
        $item->clearCustomName();
        $item->setLore([$this->convert("{date} um {time}", $date, $time, $name)."\n".$this->convert($api->getLang($sender->getName(), "SignName"), $date, $time, $name)]);
		$item->setCustomName(str_replace("&", TextFormat::ESCAPE, $fullargs));
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"SignSucces"));
        return true;
    }

    public function convert(string $string, $date, $time, $name): string{
        $string = str_replace("{date}", $date, $string);
        $string = str_replace("{time}", $time, $string);
        $string = str_replace("{name}", $name, $string);
        return $string;
	}
}