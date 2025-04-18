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
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class BlockBreak implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function break(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $worldName = $player->getWorld()->getFolderName();
        $api = new CoreAPI();
        if ($api->modules("StatsSystem") === true) {
            $api->addBreakPoints($player, 1);
            $api->addServerStats("break", 1);
        }
        if ($api->modules("Elevators") === true) {
            if ($event->getBlock()->getTypeId() == VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId() && $event->getBlock()->getTypeId() == VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId()) {
                $player->sendTip($api->getCommandPrefix("Lift") . $api->getLang($name, "EBreakSucces"));
                return true;
            }
        }
        if ($api->modules("WorldProtector") === true) {
            if (in_array($worldName, $api->getWorlds("BlockBreak"))) {
                if ($player->hasPermission(Permissions::$blockbreak)) {
                    return true;
                }
                $event->cancel();
            }
        }
        return true;
    }
}