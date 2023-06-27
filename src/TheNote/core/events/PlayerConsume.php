<?php

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class PlayerConsume implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onConsume(PlayerItemConsumeEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addConsumePoints($player, 1);
        $api->addServerStats("consume", 1);
    }
}