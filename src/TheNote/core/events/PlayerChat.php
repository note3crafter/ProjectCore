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
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\DiscordAPI;

class PlayerChat implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $message = $event->getMessage();
        $api = new CoreAPI();
        if ($api->modules("StatsSystem") === true) {
            $api->addMessagePoints($player, 1);
            $api->addServerStats("messages", 1);
        }
        //Discord
        if($api->modules("DiscordSystem") === true) {
            $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
            $all = $this->plugin->getServer()->getOnlinePlayers();
            $slots = $this->plugin->getServer()->getMaxPlayers();
            $chatprefix = $api->getDiscord("chatprefix");
            $group = $playerdata->getNested($player->getName() . ".group");
            $time = new DateTime("now", new DateTimeZone("Europe/Berlin"));
            if($api->getDiscord("Chat") === true) {
                $dc = new DiscordAPI();
                if($api->modules("GroupSystem") === true) {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $api->getDiscord("ChatMSG"));
                    $stp2 = str_replace("{msg}", $message, $stp1);
                    $stp3 = str_replace("{slots}", $slots, $stp2);
                    $player = str_replace("{gruppe}", $group, $stp3);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                } else {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $api->getDiscord("ChatMSG"));
                    $stp2 = str_replace("{msg}", $message, $stp1);
                    $player = str_replace("{slots}", $slots, $stp2);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                }
                $dc->sendMessage($player, $msg);
            }
        }
    }
}