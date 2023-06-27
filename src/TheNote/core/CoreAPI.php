<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core;

use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class CoreAPI implements Listener
{
    public static string $lang = "Lang/";
    public static string $cloud = "Cloud/";
    public static string $settings = "Settings/";
    public static string $ban = "Cloud/players/Ban/";
    public static string $gruppefile = "Cloud/players/Group/";
    public static string $heifile = "Cloud/players/Marry/";
    public static string $homefile = "Cloud/players/Homes/";
    public static string $logdatafile = "Cloud/players/Logdata/";
    public static string $scoreboardfile = "Cloud/players/Scoreboard/";
    public static string $statsfile = "Cloud/players/Stats/";
    public static string $userfile = "Cloud/players/User/";
    public static string $backfile = "Cloud/players/";

    //PlayerFinder
    public function findPlayer(CommandSender $sender, string $playerName) : ?Player{
        $subject = $sender->getServer()->getPlayerByPrefix($playerName);
        if($subject === null){
            //$sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
            return null;
        }
        return $subject;
    }

    public function modules($modul) {
        $cfg = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Modules.yml", Config::YAML);
        $cfg->get($modul);
        return $cfg->get($modul);
    }
    public function getCommandPrefix($langkey) {
        $lang = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$lang . "LangCommandPrefix.yml", Config::YAML);
        $lang->get($langkey);
        return $lang->get($langkey);
    }
    public function getLang(string $player, $langkey) {
        $api = new CoreAPI();
        $lang = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$lang . "Lang" . $api->getUser($player, "language") . ".json", Config::JSON);
        return $lang->get($langkey);
    }
    #BanSystem
    public function setBan(Player $player, $key, $data) {
        $ban = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$ban . $player->getName() . ".json", Config::JSON);
        $ban->set($key, $data);
        $ban->save();
    }
    public function getBan(string $playername, $data) {
        $ban = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$ban . $playername . ".json", Config::JSON);
        $ban->get($data);
        return $ban->get($data);
    }
    //EconomyAPI
    public function addMoney(Player $player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "Money.yml", Config::YAML);
        $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) + $amount);
        $money->save();
    }

    public function removeMoney(Player $player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "Money.yml", Config::YAML);
        $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $amount);
        $money->save();
    }

    public function getMoney(string $playername)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "Money.yml", Config::YAML);
        $money->getNested("money." . $playername);
        return $money->getNested("money." . $playername);
    }

    public function setMoney(Player $player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "Money.yml", Config::YAML);
        $money->setNested("money." . $player->getName(), $amount);
        $money->save();
    }

    public function getAllMoney()
    {
        $money = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "Money.yml", Config::YAML);
        $money->get("money", []);
    }
    //HomeAPI
    public function getHome(string $playername) {
        $home = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$homefile . $playername . ".json", Config::JSON);
        return $home->get($playername);
    }
    public function setHome(Player $player, $home, $data): bool
    {
        $h = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$homefile . $player->getName() . ".json", Config::JSON);
        $h->set($home, $data);
        $h->save();
        return true;
    }
    public function getHomeExist(string $playername, $home): bool
    {
        $h = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$homefile . $playername . ".json", Config::JSON);
        return $h->exists($home);
    }
    public function setHomeRemove(Player $player, $home): bool
    {
        $h = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$homefile . $player->getName() . ".json", Config::JSON);
        $h->remove($home);
        $h->save();
        return true;
    }
    //WarpAPI
    public function getWarp($warp) {
        $w = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "warps.json", Config::JSON);
        return $w->get($warp);
    }
    public function setWarp($warp, $data): bool
    {
        $w = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "warps.json", Config::JSON);
        $w->set($warp, $data);
        $w->save();
        return true;
    }
    public function getWarpExist($warp): bool
    {
        $w = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "warps.json", Config::JSON);
        return $w->exists($warp);
    }
    public function setWarpRemove($warp): bool
    {
        $w = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "warps.json", Config::JSON);
        $w->remove($warp);
        $w->save();
        return true;
    }
    //BackAPI
    public function getBack(string $playername) {
        $back = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$backfile . "Back.json", Config::JSON);
        return $back->get($playername);
    }
    public function getBackExist(string $playername): bool
    {
        $back = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$backfile . "Back.json", Config::JSON);
        return $back->exists($playername);
    }
    public function getMarry($player, $marry)
    {
        $hei = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$heifile . $player . ".json", Config::JSON);
        $x = $hei->get($marry);
        return $x;
    }
    public function addMarry(Player $player, $marry, $result): bool
    {
        $hei = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$heifile . $player->getName() . ".json", Config::JSON);
        $hei->set($marry, $result);
        $hei->save();
        return true;
    }

    //UserdataAPI
    public function getUser(string $user, $data)
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$userfile . $user . ".json", Config::JSON);
        return $usr->get($data);
    }

    public function setUser(Player $player, $value, $data): bool
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$userfile . $player->getName() . ".json", Config::JSON);
        $usr->set($value, $data);
        $usr->save();
        return true;
    }
    public function addUserPoint(Player $player, $amount): bool
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$userfile . $player->getName() . ".json", Config::JSON);
        $usr->set($player, $usr->get($player) + $amount);
        $usr->save();
        return true;
    }
    public function rmUserPoint(Player $player, $amount): bool
    {
        $usr = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$userfile . $player->getName() . ".json", Config::JSON);
        $usr->set($player, $usr->get($player) - $amount);
        $usr->save();
        return true;
    }
    //ConfigAPI
    public function getConfig($configdata) {
        $cfg = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Config.yml", Config::YAML);
        return $cfg->get($configdata);
    }


    //StatsAPI
    public function addJoinPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("joins", $stats->get("joins") + $points);
        $stats->save(); 
    }

    public function addBreakPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("break", $stats->get("break") + $points);
        $stats->save();
    }

    public function addPlacePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("place", $stats->get("place") + $points);
        $stats->save();
    }

    public function addKickPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("kicks", $stats->get("kicks") + $points);
        $stats->save();
    }

    public function addDeathPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("deaths", $stats->get("deaths") + $points);
        $stats->save();
    }

    public function addDropPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("drop", $stats->get("drop") + $points);
        $stats->save();
    }

    public function addMessagePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("messages", $stats->get("messages") + $points);
        $stats->save();
    }

    public function addPickPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("pick", $stats->get("pick") + $points);
        $stats->save();
    }

    public function addConsumePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("consume", $stats->get("consume") + $points);
        $stats->save();
    }

    public function addInteractPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("interact", $stats->get("interact") + $points);
        $stats->save();
    }

    public function addJumpPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("jumps", $stats->get("jumps") + $points);
        $stats->save();
    }

    public function addFlyPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("movefly", $stats->get("movefly") + $points);
        $stats->save();
    }

    public function addWalkPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("movewalk", $stats->get("movewalk") + $points);
        $stats->save();
    }

    public function addKillPoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("kills", $stats->get("kills") + $points);
        $stats->save();
    }

    public function addVotePoints(Player $player, $points)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $player->getName() . ".json", Config::JSON);
        $stats->set("votes", $stats->get("votes") + $points);
        $stats->save();
    }

    public function getJoinPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("joins");
        return $stats->get("joins");
    }

    public function getBreakPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("break");
        return $stats->get("break");
    }

    public function getPlacePoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("place");
        return $stats->get("place");
    }

    public function getKickPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("kicks");
        return $stats->get("kicks");
    }

    public function getDeathPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("deaths");
        return $stats->get("deaths");
    }

    public function getDropPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("drop");
        return $stats->get("drop");
    }

    public function getMessagePoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("messages");
        return $stats->get("messages");
    }

    public function getPickPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("pick");
        return $stats->get("pick");
    }

    public function getConsumePoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("consume");
        return $stats->get("consume");
    }

    public function getInteractPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("interact");
        return $stats->get("interact");
    }

    public function getJumpPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("jumps");
        return $stats->get("jumps");
    }

    public function getFlyPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("movefly");
        return $stats->get("movefly");
    }

    public function getWalkPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("movewalk");
        return $stats->get("movewalk");
    }

    public function getKillPoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("kills");
        return $stats->get("kills");
    }

    public function getVotePoints(string $playername)
    {
        $stats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$statsfile . $playername . ".json", Config::JSON);
        $stats->get("votes");
        return $stats->get("votes");
    }

    //ServerStats
    public function getServerStats($data) {
        $serverstats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "serverstats.json", Config::JSON);
        $serverstats->get($data);
        return $serverstats->get($data);
    }
    public function addServerStats($data, $amount) {
        $serverstats = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$cloud . "serverstats.json", Config::JSON);
        $serverstats->set($data, $serverstats->get($data) + $amount);
        $serverstats->save();
    }
}