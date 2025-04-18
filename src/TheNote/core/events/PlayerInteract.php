<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\events;

use pocketmine\block\BaseSign;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Chest;
use pocketmine\block\Door;
use pocketmine\block\DragonEgg;
use pocketmine\block\FenceGate;
use pocketmine\block\FloorSign;
use pocketmine\block\Trapdoor;
use pocketmine\block\TrappedChest;
use pocketmine\block\WallSign;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Dye;
use pocketmine\item\IceBomb;
use pocketmine\item\ItemTypeIds;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class PlayerInteract implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        $action = $event->getAction();
        $worldName = $player->getWorld()->getFolderName();
        $api = new CoreAPI();
        if ($api->modules("StatsSystem") === true) {
            $api->addInteractPoints($player, 1);
            $api->addServerStats("interact", 1);
        }
        if ($api->modules("WorldProtector") === true) {
            switch ($action) {
                case PlayerInteractEvent::LEFT_CLICK_BLOCK:
                case PlayerInteractEvent::RIGHT_CLICK_BLOCK:
                    if (in_array($worldName, $api->getWorlds("ItemFrame"))) {
                        if ($block->getTypeId() === BlockTypeIds::ITEM_FRAME || $block->getTypeId() === BlockTypeIds::GLOWING_ITEM_FRAME) {
                            if ($player->hasPermission(Permissions::$interactitemframe)) {
                                return;
                            }
                            $event->cancel();
                        }
                    }
                    if (in_array($worldName, $api->getWorlds("FlintAndSteel"))) {
                        if ($item->getTypeId() === ItemTypeIds::FLINT_AND_STEEL) {
                            if ($player->hasPermission(Permissions::$interactflintsteel)) {
                                return;
                            }
                            $event->cancel();
                        }
                    }
                    if (in_array($worldName, $api->getWorlds("SignEdit"))) {
                        if ($item instanceof Dye) {
                            if ($player->hasPermission(Permissions::$interactsign)) {
                                return;
                            }
                            $event->cancel();
                        }
                        if ($item->getTypeId() === ItemTypeIds::INK_SAC) {
                            if ($player->hasPermission(Permissions::$interactsign)) {
                                return;
                            }
                            $event->cancel();
                        }
                        if ($item->getTypeId() === ItemTypeIds::GLOW_INK_SAC) {
                            if ($player->hasPermission(Permissions::$interactsign)) {
                                return;
                            }
                            $event->cancel();
                        }
                        if ($block instanceof BaseSign || $block instanceof FloorSign || $block instanceof WallSign) {
                            if ($player->hasPermission(Permissions::$interactsign)) {
                                return;
                            }
                            $event->cancel();
                        }
                    }
                    if (in_array($worldName, $api->getWorlds("DragonEgg"))) {
                        if ($block instanceof DragonEgg) {
                            if ($player->hasPermission(Permissions::$interactdragonegg)) {
                                return;
                            }
                            $event->cancel();
                        }
                    }
                    if (in_array($worldName, $api->getWorlds("Doors"))) {
                        if ($block instanceof Door || $block instanceof Trapdoor || $block instanceof FenceGate) {
                            if ($player->hasPermission(Permissions::$interactdoors)) {
                                return;
                            }
                            $event->cancel();
                        }
                    }
                    if ($item->getTypeId() === ItemTypeIds::BONE_MEAL) {
                        if (in_array($worldName, $api->getWorlds("BoneMeal"))) {
                            if (!$player->hasPermission(Permissions::$interactbonemeal)) {
                                $event->cancel();
                            }
                        }
                    }
                    if ($item->getTypeId() === ItemTypeIds::WATER_BUCKET || $item->getTypeId() === ItemTypeIds::LAVA_BUCKET) {
                        if (in_array($worldName, $api->getWorlds("Bucket"))) {
                            if (!$player->hasPermission(Permissions::$interactbucket)) {
                                $event->cancel();
                            }
                        }
                    }
                    if ($item->getTypeId() === ItemTypeIds::HONEYCOMB) {
                        if (in_array($worldName, $api->getWorlds("HoneyComb"))) {
                            if (!$player->hasPermission(Permissions::$interacthoneycomb)) {
                                $event->cancel();
                            }
                        }
                    }
                    if ($block instanceof Chest || $block instanceof TrappedChest) {
                        if (in_array($worldName, $api->getWorlds("Chest"))) {
                            if (!$player->hasPermission(Permissions::$interactchest)) {
                                $event->cancel();
                            }
                        }
                    }

            }
        }
    }
}