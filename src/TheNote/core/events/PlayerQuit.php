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
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\listener\ScoreBoardListner;
use TheNote\core\Main;
use TheNote\core\utils\DiscordAPI;

class PlayerQuit implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onPlayerQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        //Configs
        $gruppe = new Config($this->plugin->getDataFolder() . CoreAPI::$gruppefile . $player->getName() . ".json", Config::JSON);
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        $api = new CoreAPI();
        $prefix = $playerdata->getNested($player->getName() . ".groupprefix");
        $spielername = $gruppe->get("Nickname");
        $all = $this->plugin->getServer()->getOnlinePlayers();
        $slots = $this->plugin->getServer()->getMaxPlayers();

        //QuitMessage
        if ($api->getBan($name, "ban") === true) {
            $event->setQuitMessage("");
            return;
        } elseif ($api->getConfig("QuitMessage") === true) {
            $stp1 = str_replace("{player}", $spielername, $api->getConfig("QuitMSG"));
            $stp2 = str_replace("{count}", count($all) - 1, $stp1);
            $stp3 = str_replace("{slots}", $slots, $stp2);
            $quitmsg = str_replace("{prefix}", $prefix, $stp3);
            $event->setQuitMessage($quitmsg);
        } else {
            $event->setQuitMessage("");
        }
        $api->setUser($player, "afkmove", false);
        $api->setUser($player, "afk", false);
        //Scoreboard
        if($api->modules("ScoreBoardSystem") === true) {
            if ($api->getUser($name, "sb") === true) {
                Main::getInstance()->getScheduler()->scheduleRepeatingTask(new class extends Task {
                    private $timer = 6;
                    private $i = 0;
                    public function onRun(): void {
                        $sb = new ScoreBoardListner();
                        $this->timer--;
                        if ($this->timer >= 3) {
                            if ($this->i == 2) {
                                $sb->scoreboard();
                            }
                            if ($this->i == 5) {
                                $this->i = 0;
                            }
                            $this->i++;
                        }
                    }
                }, 20);
            }
        }
        //Discord
        if($api->modules("DiscordSystem") === true) {
            $chatprefix = $api->getDiscord("chatprefix");
            $time = new DateTime("now", new DateTimeZone("Europe/Berlin"));
            $group = $playerdata->getNested($player->getName() . ".group");
            if($api->getDiscord("Quit") === true) {
                $dc = new DiscordAPI();
                if($api->modules("GroupSystem") === true) {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $api->getDiscord("QuitMSG"));
                    $stp2 = str_replace("{count}", count($all) - 1, $stp1);
                    $stp3 = str_replace("{slots}", $slots, $stp2);
                    $player = str_replace("{gruppe}", $group, $stp3);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                } else {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $api->getDiscord("KickMSG"));
                    $stp2 = str_replace("{count}", count($all) - 1, $stp1);
                    $player = str_replace("{slots}", $slots, $stp2);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                }
                $dc->sendMessage($player, $msg);
            }
        }
    }
}