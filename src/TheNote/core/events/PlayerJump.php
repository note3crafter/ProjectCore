<?php

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class PlayerJump implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onJump(PlayerJumpEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addJumpPoints($player, 1);
        $api->addServerStats("jumps", 1);
    }
}