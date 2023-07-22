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
use TheNote\core\utils\Permissions;

class NickCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("nick", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("NickDescription"), "/nick <Name>");
        $this->setPermission(Permissions::$nick);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $name = $sender->getName();
        $groups = new Config($this->plugin->getDataFolder(). CoreAPI::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if ($api->getUserGroup($name,"Nick") === true) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NickError"));
            return true;
        }
        If (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"NickUsage"));
            return true;
        }
        If (isset($args[0])) {
            $nickname = $args[0];
            $message = str_replace("{nick}", $nickname, $api->getLang($sender->getName(),"NickSucces"));
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $message);
            $api->setUserGroup($sender, "Nick", true);
            $api->setUserGroup($sender, "Nickname", $args[0]);
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $api->getUserGroup($name, "Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $api->getUserGroup($name,"Nickname"), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $sender->setDisplayName($displayname);
            $sender->setNameTag($nametag);
        }
        return true;
    }
}