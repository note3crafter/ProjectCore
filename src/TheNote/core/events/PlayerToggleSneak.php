<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\events;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\world\sound\AnvilUseSound;
use pocketmine\world\sound\EndermanTeleportSound;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class PlayerToggleSneak implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerToggleSneak(PlayerToggleSneakEvent $event) :void
    {
        $api = new CoreAPI();
        $el = new EventsListener();
        if ($api->modules("Elevators") === true) {
            if(!$event->getPlayer()->isSneaking()) return;
            $block = $event->getPlayer()->getWorld()->getBlock(new Vector3($event->getPlayer()->getPosition()->getX(), $event->getPlayer()->getPosition()->getY(), $event->getPlayer()->getPosition()->getZ()));
            if($block->getTypeId() !== VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId() && $block->getTypeId() !== VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId())
                return;
            if (isset(Main::$cooldown[$event->getPlayer()->getName()])) {
                if (Main::$cooldown[$event->getPlayer()->getName()] > time()) return;
            }
            $searchForPrivate = true;
            if($el->getElevators($block, "down", $searchForPrivate) === 0) {
                $event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(), new AnvilUseSound());
                $event->getPlayer()->sendTip($api->getCommandPrefix("Lift") . $api->getLang($event->getPlayer()->getName(), "EDownError"));
                return;
            }
            $nextElevator = $el->getNextElevator($block, "down", true);
            if($nextElevator === null) {
                $event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(), new AnvilUseSound());
                $event->getPlayer()->sendTip($api->getCommandPrefix("Lift") . $api->getLang($event->getPlayer()->getName(), "EDownNotFound"));
                return;
            }
            if($nextElevator === $block) {
                $event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(), new AnvilUseSound());
                $event->getPlayer()->sendTip($api->getCommandPrefix("Lift") . $api->getLang($event->getPlayer()->getName(), "EDownNotSafe"));
                return;
            }
            $pos = new Position($nextElevator->getPosition()->getX() + 0.5, $nextElevator->getPosition()->getY() + 1, $nextElevator->getPosition()->getZ() + 0.5, $nextElevator->getPosition()->getWorld());
            $event->getPlayer()->teleport($pos, $event->getPlayer()->getLocation()->getYaw(), $event->getPlayer()->getLocation()->getPitch());
            $elevators = $el->getElevators($block, "", $searchForPrivate);
            $floor = $el->getFloor($nextElevator, $searchForPrivate);
            $event->getPlayer()->getWorld()->addSound($event->getPlayer()->getPosition(),new EndermanTeleportSound());
            $msg = str_replace("{floor}", $floor, $api->getLang($event->getPlayer()->getName(), "EUpSucces"));
            $event->getPlayer()->sendTip($api->getCommandPrefix("Lift") . str_replace("{floortotal}", $elevators, $msg));
            Main::$cooldown[$event->getPlayer()->getName()] = time() + 1;
        }
    }
}