<?php

namespace TheNote\core\events;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

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
        $api = new CoreAPI();
        $api->addPlacePoints($player, 1);
        $api->addServerStats("place", 1);
    }
}