<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\chat\LegacyRawChatFormatter;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class GroupListener implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        $groups = new Config($this->plugin->getDataFolder(). CoreAPI::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        $defaultgroup = $groups->get("DefaultGroup");
        $groupprefix = $groups->getNested("Groups.$defaultgroup.groupprefix");
        $player = $event->getPlayer();
        $name = $player->getName();
        $pf = new Config($this->plugin->getDataFolder() . CoreAPI::$gruppefile . $player->getName() . ".json", Config::JSON);

        $event->getPlayer()->setDisplayName("");
        if (!$playerdata->exists($name)) {
            $playerdata->setNested($name . ".groupprefix", $groupprefix);
            $playerdata->setNested($name . ".groupprefix", $groupprefix );
            $playerdata->setNested($name . ".group", $defaultgroup);
            $perms = $playerdata->getNested("{$name}.permissions", []);
            $perms[] = Permissions::$defaultperm;
            $playerdata->setNested("{$name}.permissions", $perms);
            $playerdata->save();
        }

        $playergroup = $playerdata->getNested($name . ".group");
        $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
        $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
        $player->setNameTag($nametag);
        $player->setDisplayName($displayname);

        //Group Perms
        $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
        foreach ($permissionlist as /*$name =>*/ $data) {
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }

        //User Perms.
        $perms = (array)$playerdata->getNested("{$name}.permissions", []);
        foreach ($perms as/* $name =>*/ $data) {
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }
    }

    public function onPlayerChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $groups = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud. "players.yml", Config::YAML);
        $msg = $event->getMessage();
        $pf = new Config($this->plugin->getDataFolder() . CoreAPI::$gruppefile . $player->getName() . ".json", Config::JSON);
        $clan = new Config($this->plugin->getDataFolder() . CoreAPI::$gruppefile . $player->getName() . ".json", Config::JSON);
        $playergroup = $playerdata->getNested($name . ".group");
        $groupprefix = $groups->getNested("Groups.$playergroup.groupprefix");

        $getformatone = $groups->getNested("Groups." . $playergroup . ".format1");
        $getformattwo = $groups->getNested("Groups." . $playergroup . ".format2");
        $getformattree = $groups->getNested("Groups." . $playergroup . ".format3");
        $getformatfour = $groups->getNested("Groups." . $playergroup . ".format4");


        $clan2 = $clan->get("Clan");
        $clan3 = "§f[§d" . $clan2 . "§r§f]";
        $nick = $pf->get("Nickname");
        $heirat = "§f[§ao§f]";

        //NameTag1
        $a = str_replace("{msg}", $msg, $getformatone);
        $b = str_replace("{name}", $nick, $a);
        $c = str_replace("{group}", $groupprefix, $b);
        //NameTag2
        $aa = str_replace("{msg}", $msg, $getformattwo);
        $bb = str_replace("{name}", $nick, $aa);
        $cc = str_replace("{clan}", $clan3, $bb);
        $dd = str_replace("{group}", $groupprefix, $cc);
        //NameTag3
        $aaa = str_replace("{msg}", $msg, $getformattree);
        $bbb = str_replace("{name}", $nick, $aaa);
        $ccc = str_replace("{heirat}", $heirat, $bbb);
        $ddd = str_replace("{group}", $groupprefix, $ccc);
        //NameTag4
        $aaaa = str_replace("{name}", $nick , $getformatfour);
        $bbbb = str_replace("{clan}", $clan3, $aaaa);
        $cccc = str_replace("{heirat}", $heirat, $bbbb);
        $dddd = str_replace("{msg}", $msg, $cccc);
        $eeee = str_replace("{group}", $groupprefix, $dddd);

        if ($pf->get("Nick") === true) {
            if ($clan->get("ClanStatus") === true) {
                if ($api->getMarry($player->getName(), "marry") === true) {
                    $event->setFormatter(new LegacyRawChatFormatter($eeee));
                } elseif ($api->getMarry($player->getName(), "marry") === false) {
                    $event->setFormatter(new LegacyRawChatFormatter($dd));
                }
            } elseif ($clan->get("ClanStatus") === false) {
                if ($api->getMarry($player->getName(), "marry") === true) {
                    $event->setFormatter(new LegacyRawChatFormatter($ddd));
                } elseif ($api->getMarry($player->getName(), "marry") === false) {
                    $event->setFormatter(new LegacyRawChatFormatter($c));
                }
            }
        }
        if ($pf->get("Nick") === false or null) {
            if ($clan->get("ClanStatus") === true) {
                if ($api->getMarry($player->getName(), "marry") === true) {
                    $event->setFormatter(new LegacyRawChatFormatter($eeee));
                } elseif ($api->getMarry($player->getName(), "marry") === false) {
                    $event->setFormatter(new LegacyRawChatFormatter($dd));
                }
            }
            if ($clan->get("ClanStatus") === false) {
                if ($api->getMarry($player->getName(), "marry") === true) {
                    $event->setFormatter(new LegacyRawChatFormatter($ddd));
                } elseif ($api->getMarry($player->getName(), "marry") === false) {
                    $event->setFormatter(new LegacyRawChatFormatter($c));
                }
            }
        }
    }
}