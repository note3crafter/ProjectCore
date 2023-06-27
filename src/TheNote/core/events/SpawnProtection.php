<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;
use TheNote\core\CoreAPI;
use TheNote\core\utils\Permissions;

class SpawnProtection implements Listener
{
    private $radiusSquared;

    public function __construct(int $radius)
    {
        $this->radiusSquared = $radius ** 2;
    }
    private function checkSpawnProtection(World $world, Player $player, Vector3 $vector): bool
    {
        if (!$player->hasPermission(Permissions::$spawnprotectionbypass)) {
            $t = new Vector2($vector->x, $vector->z);

            $spawnLocation = $world->getSpawnLocation();
            $s = new Vector2($spawnLocation->x, $spawnLocation->z);
            if ($t->distanceSquared($s) <= $this->radiusSquared) {
                return true;
            }
        }
        return false;
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $api = new CoreAPI();
        if($api->getConfig("NoPVP") === true) {
            if ($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())) {
                $event->cancel();
            }
        }
    }
    public function onDamage(EntityDamageEvent $event): void
    {
        $player = $event->getEntity();
        $api = new CoreAPI();
        if($api->getConfig("NoPVP") === true) {
            if ($player instanceof Player) {
                if ($this->checkSpawnProtection($player->getWorld(), $player, $player->getPosition())) {
                    $event->cancel();
                }
                if ($player->hasPermission(Permissions::$spawnprotectionbypass)) {
                    $event->cancel();
                }
            }
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        foreach ($event->getTransaction()->getBlocks() as [$x, $y, $z, $block]) {
            if ($this->checkSpawnProtection($world, $player, new Vector3($x, $y, $z))) {
                $event->cancel();
                return;
            }
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void
    {
        if ($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())) {
            $event->cancel();
        }
    }

    public function onSignChange(SignChangeEvent $event): void
    {
        if ($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())) {
            $event->cancel();
        }
    }
    public function onDrop(PlayerDropItemEvent $event):void {
        $api = new CoreAPI();
        if($api->getConfig("NoDrop") === true) {
            if ($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getItem()->getBlock()->getPosition())) {
                $event->cancel();
            }
        }
    }
}