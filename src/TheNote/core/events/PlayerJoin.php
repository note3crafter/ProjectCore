<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\events;

use DateTime;
use DateTimeZone;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\StringToItemParser;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\listener\ScoreBoardListner;
use TheNote\core\Main;
use TheNote\core\utils\DiscordAPI;
use TheNote\core\utils\Permissions;

class PlayerJoin implements Listener
{

    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        //Allgemeines
        $player = $event->getPlayer();
        $name = $player->getName();
        $fj = date('d.m.Y H:I') . date_default_timezone_set("Europe/Berlin");

        //Configs
        $log = new Config($this->plugin->getDataFolder() . CoreAPI::$logdatafile . $name . ".json", Config::JSON);
        $stats = new Config($this->plugin->getDataFolder() . CoreAPI::$statsfile . $name . ".json", Config::JSON);
        $cfg = new Config($this->plugin->getDataFolder() . CoreAPI::$settings . "StarterKit.yml", Config::YAML, array());
        $groups = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        $api = new CoreAPI();
        $events = new EventsListener();

        //Stats
        if($api->modules("StatsSystem") === true) {
            $api->addJoinPoints($player, 1);
            $api->addServerStats("joins", 1);
        }

        //Weiteres
        $log->set("Name", $player->getName());
        $log->set("last-IP", $player->getNetworkSession()->getIp());
        $log->set("last-XboxID", $player->getPlayerInfo()->getXuid());
        $log->set("last-online", $fj);
        if ($api->modules("MSGSystem") === true) {
            if ($api->getUser($name,"nodm") === true) {
                $player->sendMessage($api->getCommandPrefix("TPA") . $api->getLang($name, "NoDMJoinMessage"));
            }
        }
        if($api->getBan($name,"ban") === false or null) {
            if ($api->getConfig("JoinLightning") === true) {
                $events->addStrike($player);
            }
            if ($api->getConfig("JoinTotem") === true) {
                $events->TotemEffect($player);
            }

        }

        //Spieler Erster Join
        if ($api->getUser($name, "register") == null or false) {
            //StarterKit
            $player = $event->getPlayer();
            $ainv = $player->getArmorInventory();
            if ($api->getConfig("StarterKit") === true) {
                if ($cfg->get("Inventory") === true) {
                    foreach ($cfg->get("Slots", []) as $slot) {
                        $item = StringToItemParser::getInstance()->parse($slot["item"]);
                        $item->setCount($slot["count"]);
                        $item->setCustomName($slot["name"]);
                        $item->setLore([$slot["lore"]]);
                        $player->getInventory()->addItem($item);
                    }
                }
                if ($cfg->get("Armor") === true) {
                    $data = $cfg->get("helm");
                    $item = StringToItemParser::getInstance()->parse($data["item"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setHelmet($item);

                    $data = $cfg->get("chest");
                    $item = StringToItemParser::getInstance()->parse($data["item"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setChestplate($item);

                    $data = $cfg->get("leggins");
                    $item = StringToItemParser::getInstance()->parse($data["item"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setLeggings($item);

                    $data = $cfg->get("boots");
                    $item = StringToItemParser::getInstance()->parse($data["item"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setBoots($item);
                }
            }
            //Groupsystem
            if ($api->modules("GroupSystem") === true) {

                $defaultgroup = $groups->get("DefaultGroup");
                $groupprefix = $groups->getNested("Groups." . $defaultgroup . ".groupprefix");
                $groupdisplay = $groups->getNested("Groups." . $defaultgroup . ".displayname");
                if (!$playerdata->exists($name)) {
                    $playerdata->setNested($name . ".groupprefix", $groupprefix);
                    $playerdata->setNested($name . ".group", $defaultgroup);
                    $playerdata->setNested($name . ",displayname", $groupdisplay);
                    $perms = $playerdata->getNested("{$name}.permissions", []);
                    $perms[] = Permissions::$defaultperm;
                    $playerdata->setNested("{$name}.permissions", $perms);
                    $playerdata->save();
                }
                $api->setUserGroup($player, "GroupPrefix", $groupprefix);
                $api->setUserGroup($player, "Group", $defaultgroup);
                $api->setUserGroup($player, "Nick", false);
                $api->setUserGroup($player, "NickPlayer", false);
                $api->setUserGroup($player, "Nickname", $player->getName());
                $playergroup = $playerdata->getNested($name . ".group");
                $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.groupprefix"));
                $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
                $player->setNameTag($nametag);
                $player->setDisplayName($displayname);

                //Group Perms
                $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
                foreach ($permissionlist as $name => $data) {
                    $player->addAttachment($this->plugin)->setPermission($data, true);
                }
            }

            //Economy
            if ($api->modules("EconomySystem") === true) {
                $amount = $api->getConfig("DefaultMoney");
                if ($api->getMoney($name) == null) {
                    $api->setMoney($player, $amount);
                }
            }
            //Register
            $api->addServerStats("Users", 1);
            $log->set("first-join", $fj);
            $log->set("first-ip", $player->getNetworkSession()->getIp());
            $log->set("first-XboxID", $player->getXuid());
            $log->set("first-uuid", $player->getUniqueId());
            $log->save();

            $api->addMarry($player, "partner", "No Partner");
            $api->addMarry($player, "application", "No Inquiry");
            $api->addMarry($player, "status", "Single");
            $api->addMarry($player, "hits", 0);
            $api->addMarry($player, "divorces", 0);
            $api->addMarry($player, "marrypoints", 0);
            $api->addMarry($player, "denieds", 0);
            $api->addMarry($player, "marry", false);
            $api->addMarry($player, "marryapplication", false);
            $api->setUser($player, "nodm", false);
            $api->setUser($player, "heistatus", false);
            $api->setUser($player, "registermarry", false);
            $api->setUser($player, "starterkit", false);
            $api->setUser($player, "afkmove", false);
            $api->setUser($player, "afk", false);
            $api->setUser($player, "heistatus", false);
            $api->setUser($player, "register", true);
            $api->setUser($player, "Clan", "No Clan");
            $api->setUser($player, "Clananfrage", false);
            $api->setUser($player,"nodm", false);
            $api->setUser($player,"heistatus", false);
            $api->setUser($player,"starterkit", true);
            $api->setUser($player, "language", "DEU");
            $api->setUser($player, "sb", true);
            $api->setBan($player,"bannedby", "");
            $api->setBan($player,"banreason", "");
            $api->setBan($player,"banid", "");
            $api->setBan($player,"bantime", "");
            $api->setBan($player,"ban", false);
            $stats->set("joins", 0);
            $stats->set("break", 0);
            $stats->set("place", 0);
            $stats->set("drop", 0);
            $stats->set("pick", 0);
            $stats->set("interact", 0);
            $stats->set("jumps", 0);
            $stats->set("messages", 0);
            $stats->set("votes", 0);
            $stats->set("consume", 0);
            $stats->set("kicks", 0);
            $stats->set("erfolge", 0);
            $stats->set("movefly", 0);
            $stats->set("movewalk", 0);
            $stats->save();
            $api->setUserGroup($player, "Nickname", $player->getName());
        }

        //JoinMessages
        $all = $this->plugin->getServer()->getOnlinePlayers();
        $prefix = $playerdata->getNested($player->getName() . ".groupprefix");
        $slots = $this->plugin->getServer()->getMaxPlayers();
        $spielername = $api->getUserGroup($name, "Nickname");
        if($api->getBan($name,"ban") === true) {
            $event->setJoinMessage("");
            return;
        } elseif ($api->getConfig("JoinTitle") === true) { //JoinTitle
            $subtitle = str_replace("{player}", $player->getName(), $api->getConfig("Subtitlemsg"));
            $title = str_replace("{player}", $player->getName(), $api->getConfig("Titlemsg"));
            $player->sendTitle($title);
            $player->sendSubTitle($subtitle);
        }
        if ($api->getConfig("JoinTip") === true) { //JoinTip
            $tip = str_replace("{player}", $player->getName(), $api->getConfig("Tipmsg"));
            $player->sendTip($tip);
        }
        if ($api->getConfig("JoinMessage") === true) { //Joinmessage
            if ($spielername == null) {
                $stp1 = str_replace("{player}", $player->getName(), $api->getConfig("JoinMSG"));
            } else {
                $stp1 = str_replace("{player}", $spielername, $api->getConfig("JoinMSG"));
            }
            $stp2 = str_replace("{count}", count($all), $stp1);
            $stp3 = str_replace("{slots}", $slots, $stp2);
            $joinmsg = str_replace("{prefix}", $prefix, $stp3);
            $event->setJoinMessage($joinmsg);
        } else {
            $event->setJoinMessage("");
        }
        //Discord
        if($api->modules("DiscordSystem") === true) {
            $dcsettings = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Discord.yml", Config::YAML);
            $chatprefix = $dcsettings->get("chatprefix");
            $group = $playerdata->getNested($player->getName() . ".group");
            $time = new DateTime("now", new DateTimeZone("Europe/Berlin"));
            if($dcsettings->get("Join") === true) {
                $dc = new DiscordAPI();
                if($api->modules("GroupSystem") === true) {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("JoinMSG"));
                    $stp2 = str_replace("{count}", count($all), $stp1);
                    $stp3 = str_replace("{slots}", $slots, $stp2);
                    $player = str_replace("{gruppe}", $group, $stp3);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                } else {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("JoinMSG"));
                    $stp2 = str_replace("{count}", count($all), $stp1);
                    $player = str_replace("{slots}", $slots, $stp2);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                }
                $dc->sendMessage($player, $msg);
            }
        }

        //Scoreboard
        if($api->modules("ScoreBoardSystem") === true) {
            if($api->getUser($name, "sb") === true){
                $sb = new ScoreBoardListner();
                $sb->scoreboard();
            }
        }
    }
}