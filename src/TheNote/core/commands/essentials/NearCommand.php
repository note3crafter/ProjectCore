<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\essentials;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class NearCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("near", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("NearDescription"), "/near");
        $this->setPermission(Permissions::$near);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }
        $who = $sender ? "you" : $sender->getDisplayName();
        if (count($near = $this->getNearPlayers($sender)) < 1) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), str_replace($who, "{data}", "NearNoPlayers")));
        } else {
            $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(), str_replace(count($near), "{count}", "NearPlayers")));
            foreach ($near as $p) {
                $sender->sendMessage("§e\n*§r " . $p->getNameTag());
            }
        }
        return true;
    }

    public function getNearPlayers(Player $player, int $radius = null): ?array
    {
        if ($radius == null || !is_numeric($radius)) {
            $radius = 100;
        }
        if (!is_numeric($radius)) {
            return null;
        }
        $players = [];
        foreach ($player->getWorld()->getNearbyEntities(new AxisAlignedBB($player->getPosition()->getFloorX() - $radius, $player->getPosition()->getFloorY() - $radius, $player->getPosition()->getFloorZ() - $radius, $player->getPosition()->getFloorX() + $radius, $player->getPosition()->getFloorY() + $radius, $player->getPosition()->getFloorZ() + $radius), $player) as $e) {
            if ($e instanceof Player) {
                $players[] = $e;
            }
        }
        return $players;
    }
}