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
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class AFKCommand extends Command implements Listener
{
    private $plugin;
    private $afk;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("afk", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("AFKDescription"), "/afk");
        $this->afk = array();
        $this->setPermission(Permissions::$defaultperm);

    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $pf = new Config($this->plugin->getDataFolder() . CoreAPI::$gruppefile . $sender->getName() . ".json", Config::JSON);
        $groups = new Config($this->plugin->getDataFolder(). CoreAPI::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (isset($this->afk[strtolower($sender->getName())])) {
            unset($this->afk[strtolower($sender->getName())]);
            $sender->sendMessage($api->getCommandPrefix("AFK") . $api->getLang($sender->getName(), "AfkNoAfk"));
            $sender->setNoClientPredictions(false);
            $api->setUser($sender, "afkmove", false);
            $api->setUser($sender, "afk", false);
            $playergroup = $playerdata->getNested($sender->getName() . ".group");
            $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($sender->getName().".group")}.displayname"));
            $sender->setDisplayName($displayname);
            $sender->setNameTag($nametag);
        } else {
            $this->afk[strtolower($sender->getName())] = strtolower($sender->getName());
            $sender->sendMessage($api->getCommandPrefix("AFK") . $api->getLang($sender->getName(), "AfkNowAfk"));
            $sender->setNoClientPredictions(true);
            $api->setUser($sender, "afkmove", true);
            $api->setUser($sender, "afk", true);
            $playergroup = $playerdata->getNested($sender->getName() . ".group");
            $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($sender->getName().".group")}.displayname"));
            $sender->setDisplayName("§f[§6AFK§f] " . $displayname);
            $sender->setNameTag("§f[§6AFK§f] " . $nametag);
        }
        return true;
    }
    public function onQuit(PlayerQuitEvent $event){
        $api = new CoreAPI();
        $player = $event->getPlayer();
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        $pf = new Config($this->plugin->getDataFolder() . CoreAPI::$gruppefile . $player->getName() . ".json", Config::JSON);
        $groups = new Config($this->plugin->getDataFolder(). CoreAPI::$cloud . "groups.yml", Config::YAML);
        $api->setUser($player, "afkmove", false);
        $api->setUser($player, "afk", false);
        $playergroup = $playerdata->getNested($player->getName() . ".group");
        $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
        $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($player->getName().".group")}.displayname"));
        $player->setDisplayName($displayname);
        $player->setNameTag($nametag);
        --Main::$afksesion[$player->getName()];
    }


    public function onMove(PlayerMoveEvent $event) {
        $api = new CoreAPI();
        $player = $event->getPlayer();
        if($api->getUser($player->getName(), "afkmove") === true) {
            $player->sendMessage($api->getLang($player->getName(), "AfkNoMove"));
            $event->cancel();
        }
    }
}