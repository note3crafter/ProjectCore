<?php

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class PlayerChat implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addMessagePoints($player, 1);
        $api->addServerStats("messages", 1);
    }
}