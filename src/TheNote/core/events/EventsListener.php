<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\events;

use pocketmine\block\Block;
use pocketmine\block\DaylightSensor;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\animation\TotemUseAnimation;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBroadcastUtils;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\player\Player;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\sound\TotemUseSound;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class EventsListener implements Listener
{

    public function remove(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player && isset(Main::$godmod[$entity->getName()])) {
            if (Main::$godmod[$entity->getName()]) {
                $event->cancel();
            }
        }
        if ($entity instanceof Player && isset(Main::$godmod[$entity->getName()])) {
            if (Main::$vanish[$entity->getName()]) {
                $event->cancel();
            }
        }
    }
    public function addStrike(Player $player): void
    {
        $light = AddActorPacket::create(($entityId = Entity::nextRuntimeId()), $entityId, "minecraft:lightning_bolt", new Vector3(($pos = $player->getPosition())->getX(), $pos->getY(), $pos->getZ()), null, 0, 0, 0.0, 0.0, [], [], new PropertySyncData([], []), []);
        $player->getWorld()->addParticle($pos, new BlockBreakParticle($player->getWorld()->getBlock($player->getPosition()->floor()->down())), $player->getWorld()->getPlayers());
        $sound = PlaySoundPacket::create("ambient.weather.thunder", $pos->getX(), $pos->getY(), $pos->getZ(), 1, 1);
        NetworkBroadcastUtils::broadcastPackets($player->getWorld()->getPlayers(), [$light, $sound]);
    }
    public function TotemEffect(Player $player)
    {
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->setItemInHand(VanillaItems::TOTEM());
        $player->broadcastAnimation(new TotemUseAnimation($player));
        $player->getWorld()->addSound($player->getPosition(), new TotemUseSound());
        $player->getInventory()->setItemInHand($item);
    }
    //LiftSystem
    public function getElevators(Block $block, string $where = "", bool $searchForPrivate = false): int
    {
        if (!$searchForPrivate) {
            $blocks = DaylightSensor::class;
        } else {
            $blocks = [VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId(), VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId()];
        }
        $count = 0;
        if ($where === "up") {
            $y = $block->getPosition()->getY() + 1;
            while ($y < $block->getPosition()->getWorld()->getMaxY()) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getTypeId(), $blocks)) {
                    $count = $count + 1;
                }
                $y++;
            }
        } elseif ($where === "down") {
            $y = $block->getPosition()->getY() - 1;
            while ($y >= 0) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getTypeId(), $blocks)) {
                    $count = $count + 1;
                }
                $y--;
            }
        } else {
            $y = 0;
            while ($y < $block->getPosition()->getWorld()->getMaxY()) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getTypeId(), $blocks)) {
                    $count = $count + 1;
                }
                $y++;
            }
        }
        return $count;
    }


    public function getNextElevator(Block $block, string $where = "", bool $searchForPrivate = false): ?Block
    {
        if (!$searchForPrivate) {
            $blocks = [VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId()];
        } else {
            $blocks = [VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId(), VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId()];
        }
        $elevator = null;
        if ($where === "up") {
            $y = $block->getPosition()->getFloorY() + 1;
            while ($y < $block->getPosition()->getWorld()->getMaxY()) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getTypeId(), $blocks)) {
                    $elevator = $blockToCheck;
                    break;
                }
                $y++;
            }
        } else {
            $y = $block->getPosition()->getY() - 1;
            while ($y >= 0) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getTypeId(), $blocks)) {
                    $elevator = $blockToCheck;
                    break;
                }
                $y--;
            }
        }
        if ($elevator === null) return null;
        $api = new CoreAPI();
        if ($api->getConfig("CheckFloor") !== true) return $elevator;

        $block1 = $elevator->getPosition()->getWorld()->getBlock(new Vector3($elevator->getPosition()->getX(), $elevator->getPosition()->getY() + 1, $elevator->getPosition()->getZ()));
        $block2 = $elevator->getPosition()->getWorld()->getBlock(new Vector3($elevator->getPosition()->getX(), $elevator->getPosition()->getY() + 2, $elevator->getPosition()->getZ()));
        if ($block1->getTypeId() !== 0 || $block2->getTypeId() !== 0) return $block;

        $blocksToCheck = [];

        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX() + 1, $block1->getPosition()->getY(), $block1->getPosition()->getZ()));
        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX() - 1, $block1->getPosition()->getY(), $block1->getPosition()->getZ()));
        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX(), $block1->getPosition()->getY(), $block1->getPosition()->getZ() + 1));
        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX(), $block1->getPosition()->getY(), $block1->getPosition()->getZ() - 1));

        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX() + 1, $block2->getPosition()->getY(), $block2->getPosition()->getZ()));
        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX() - 1, $block2->getPosition()->getY(), $block2->getPosition()->getZ()));
        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX(), $block2->getPosition()->getY(), $block2->getPosition()->getZ() + 1));
        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX(), $block2->getPosition()->getY(), $block2->getPosition()->getZ() - 1));

        $deniedBlocks = [VanillaBlocks::LAVA()->getTypeId(), VanillaBlocks::WATER()->getTypeId()];
        foreach ($blocksToCheck as $blockToCheck) {
            if (in_array($blockToCheck->getId(), $deniedBlocks)) return $block;
        }

        return $elevator;
    }

    public function getFloor(Block $block, bool $searchForPrivate = false): int
    {
        if (!$searchForPrivate) {
            $blocks = [VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId()];
        } else {
            $blocks = [VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId(), VanillaBlocks::DAYLIGHT_SENSOR()->getTypeId()];
        }
        $sw = 0;
        $y = -1;
        while ($y < $block->getPosition()->getWorld()->getMaxY()) {
            $y++;
            $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
            if (!in_array($blockToCheck->getTypeId(), $blocks)) continue;
            $sw++;
            if ($blockToCheck === $block) break;
        }
        return $sw;
    }
}