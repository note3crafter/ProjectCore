<?php

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

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
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addInteractPoints($player, 1);
        $api->addServerStats("interact", 1);
    }
}