<?php

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class PlayerKick implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onKick(PlayerKickEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addKickPoints($player, 1);
        $api->addServerStats("kicks", 1);
    }
}