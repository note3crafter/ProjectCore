<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\utils;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class Manager implements Listener
{
    public static function formateString(Main $plugin, Player $player, string $string): string
    {
        $playerdata = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        $api = new CoreAPI();
        $device = new Device();
        $string = str_replace([
            "{clan}",
            "{marry}",
            "{rank}",
            "{money}",
            "{ping}",
            "{tps}",
            "{name}",
            "{online}",
            "{max_online}",
            "{world}",
            "{x}",
            "{y}",
            "{z}",
            "{ip}",
            "{port}",
            "{uid}",
            "{xuid}",
            "{health}",
            "{max_health}",
            "{food}",
            "{max_food}",
            "{gamemode}",
            "{scale}",
            "{xplevel}",
            "{id}",
            "{count}",
            "{kicks}",
            "{joins}",
            "{breaks}",
            "{places}",
            "{drops}",
            "{picks}",
            "{interacts}",
            "{jumps}",
            "{messages}",
            "{votes}",
            "{flymeters}",
            "{walkmeters}",
            "{deaths}",
            "{consumes}",
            "{deviceos}",
            /*"{playtime}"*/],
            [$api->getUser($player->getName(), "Clan")
                , $api->getMarry($player->getName(), "partner")
                , $playerdata->getNested($player->getName() . ".groupprefix")
                , $api->getMoney($player->getName())
                , $player->getNetworkSession()->getPing()
                , Server::getInstance()->getTicksPerSecond()
                , $player->getName()
                , count(Server::getInstance()->getOnlinePlayers())
                , Server::getInstance()->getMaxPlayers()
                , $player->getWorld()->getFolderName()
                , round($player->getLocation()->getFloorX())
                , round($player->getLocation()->getFloorY())
                , round($player->getLocation()->getFloorZ())
                , $player->getNetworkSession()->getIp()
                , $player->getNetworkSession()->getPort()
                , $player->getUniqueId()
                , $player->getXuid()
                , $player->getHealth()
                , $player->getMaxHealth()
                , $player->getHungerManager()->getFood()
                , $player->getHungerManager()->getMaxFood()
                , $player->getGamemode()->getEnglishName()
                , $player->getScale()
                , $player->getXpManager()->getXpLevel()
                , $player->getInventory()->getItemInHand()->getTypeId()
                , $player->getInventory()->getItemInHand()->getCount()
                , $api->getKickPoints($player->getName())
                , $api->getJoinPoints($player->getName())
                , $api->getBreakPoints($player->getName())
                , $api->getPlacePoints($player->getName())
                , $api->getDropPoints($player->getName())
                , $api->getPickPoints($player->getName())
                , $api->getInteractPoints($player->getName())
                , $api->getJumpPoints($player->getName())
                , $api->getMessagePoints($player->getName())
                , $api->getVotePoints($player->getName())
                , round($api->getFlyPoints($player->getName()))
                , round($api->getWalkPoints($player->getName()))
                , $api->getDeathPoints($player->getName())
                , $api->getConsumePoints($player->getName())
                , $device->getPlayerPlatform($player)
                , /*$main->getDatabase()->getRawTime($player->getName())*/]
            , $string);
        return $string;
    }
}