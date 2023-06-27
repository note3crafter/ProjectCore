<?php
//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\events;

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
}