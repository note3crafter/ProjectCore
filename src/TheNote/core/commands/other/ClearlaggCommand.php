<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\other;

use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\object\ItemEntity;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use TheNote\core\utils\Permissions;

class ClearlaggCommand extends Command
{
    public bool $clearItems;
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
		$api = new CoreAPI();
        parent::__construct("clearlagg", $api->getCommandPrefix("prefix") . $api->getCommandPrefix("ClearLaggDescription"), "/clearlagg", ["cl", "clagg"]);
        $this->setPermission(Permissions::$clearlagg);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args):bool
    {
        $api = new CoreAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }
        $this->clearItems = true;
        foreach ($this->plugin->getServer()->getWorldManager()->getWorlds() as $level) {
            foreach ($level->getEntities() as $entity) {
                if ($this->clearItems && $entity instanceof ItemEntity) {
                    if (!$entity instanceof Human){
                        $entity->flagForDespawn();
                    }
                }
                if ($this->clearItems && ($entity instanceof Entity)) {
                    if (!$entity instanceof Human){
                        $entity->flagForDespawn();
                    }
                }
            }
        }
        $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"ClearLaggSucces"));
        return true;
    }
}