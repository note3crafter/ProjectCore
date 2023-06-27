<?php

namespace TheNote\core\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class BlockBreak implements Listener
{
    public Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function break(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $api = new CoreAPI();
        $api->addBreakPoints($player, 1);
        $api->addServerStats("break", 1);
    }
}