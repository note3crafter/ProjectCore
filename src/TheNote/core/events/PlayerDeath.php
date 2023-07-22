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
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\DiscordAPI;

class PlayerDeath implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onDeath(PlayerDeathEvent $event): void
    {
        $api = new CoreAPI();
        $el = new EventsListener();
        $player = $event->getPlayer();
        $name = $player->getName();
        if ($api->getConfig("KeepInventory") === true) {
            $event->setKeepInventory(true);
        }
        if($api->modules("StatsSystem") === true) {
            $api->addDeathPoints($player, 1);
            $api->addServerStats("deaths", 1);
            $cause = $player->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    $api->addServerStats("kills", 1);
                    $api->addKillPoints($player, 1);
                }
            }
        }
        $cause = $player->getLastDamageCause();
        if ($cause->getCause() == EntityDamageEvent::CAUSE_CONTACT) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_contact"));
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_entity_attack"));
            $el->addStrike($player);
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_PROJECTILE) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_projectile"));
            $el->addStrike($player);
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_SUFFOCATION) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_suffocation"));
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_FIRE) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_fire"));
            $el->addStrike($player);
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_FIRE_TICK) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_fire_tick"));
            $el->addStrike($player);
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_LAVA) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_lava"));
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_DROWNING) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_drowning"));
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_EXPLOSION || $cause->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_explosion"));
            $el->addStrike($player);
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_VOID) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_void"));
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_SUICIDE) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_suicide"));
            $el->addStrike($player);
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_MAGIC) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_magic"));
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_FALL) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_fall"));
        } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_FALLING_BLOCK) {
            $event->setDeathMessage($name . $api->getCommandPrefix("cause_falling_block"));
        }

        //Discord
        if($api->modules("DiscordSystem") === true) {
            $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
            $all = $this->plugin->getServer()->getOnlinePlayers();
            $slots = $this->plugin->getServer()->getMaxPlayers();
            $dcsettings = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Discord.yml", Config::YAML);
            $chatprefix = $dcsettings->get("chatprefix");
            $group = $playerdata->getNested($player->getName() . ".group");
            $time = new DateTime("now", new DateTimeZone("Europe/Berlin"));
            if($dcsettings->get("Death") === true) {
                $dc = new DiscordAPI();
                if($api->modules("GroupSystem") === true) {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("DeathMSG"));
                    $stp2 = str_replace("{count}", count($all), $stp1);
                    $stp3 = str_replace("{slots}", $slots, $stp2);
                    $player = str_replace("{gruppe}", $group, $stp3);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                } else {
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("KickMSG"));
                    $stp2 = str_replace("{count}", count($all), $stp1);
                    $player = str_replace("{slots}", $slots, $stp2);
                    $msg = str_replace("{time}", $time->format("H:i"), str_replace("{player}", $name, $player));
                }
                $dc->sendMessage($player, $msg);
            }
        }
    }
}