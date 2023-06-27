<?php

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class PlayerDrop implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addDropPoints($player, 1);
        $api->addServerStats("drop", 1);
    }
}