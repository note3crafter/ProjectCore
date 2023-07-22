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
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class UnnickCommand extends Command
{

    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("unnick", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("UnnickDescription"), "/unnick");
        $this->setPermission("core.command.nick");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $name = $sender->getName();
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder(). CoreAPI::$cloud . "groups.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if ($api->getUserGroup($name,"Nick") === false) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"UnnickError"));
            return true;
        }
        if ($api->getUserGroup($name,"Nick") === true) {
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"UnnickSucces"));
            $api->setUserGroup($sender,"Nick", false);
            $api->setUserGroup($sender,"Nickname", $sender->getName());
            $name = $sender->getName();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $api->getUserGroup($name,"Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}",$api->getUserGroup($name,"Nickname"), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $sender->setDisplayName($displayname);
            $sender->setNameTag($nametag);
        }
        return true;
    }
}