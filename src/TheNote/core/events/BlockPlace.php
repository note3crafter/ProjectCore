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
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class BlockPlace implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function place(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $worldName = $player->getWorld()->getFolderName();
        $api = new CoreAPI();
        if ($api->modules("StatsSystem") === true) {
            $api->addPlacePoints($player, 1);
            $api->addServerStats("place", 1);
        }
        if ($api->modules("Elevators") === true) {
            if ($event->getBlockAgainst()->getTypeId() == VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId() && $event->getBlockAgainst()->getTypeId() == VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId()) {
                $player->sendTip($api->getCommandPrefix("Lift") . $api->getLang($name, "EPlaceSucces"));
                return true;
            }
        }
        if ($api->modules("WorldProtector") === true) {
            if (in_array($worldName, $api->getWorlds("BlockPlace"))) {
                if ($player->hasPermission(Permissions::$blockplace)) {
                    return true;
                }
                $event->cancel();
            }
        }
        return true;
    }
}