<?php

namespace TheNote\core\events;

use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBlockPickEvent;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class PlayerPick implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onPick(PlayerBlockPickEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addPickPoints($player, 1);
        $api->addServerStats("pick", 1);
    }

    public function onPickItem(EntityItemPickupEvent $event)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $pl) {
            $api = new CoreAPI();
            $api->addPickPoints($pl, 1);
            $api->addServerStats("pick", 1);
        }
    }
}