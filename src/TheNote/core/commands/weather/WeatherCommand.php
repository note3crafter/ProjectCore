<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\weather;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;
use TheNote\core\world\weather\WeatherManager;

class WeatherCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("weather", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("WeatherDescription"), "/weather", ["clear", "rain", "thunder"]);
        $this->setPermission(Permissions::$weather);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return;
        }
        $duration = 6000;

        $weathers = [];
        if (!$sender instanceof Player) {
            foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
                if (($weather = WeatherManager::getInstance()->getWeather($world)) !== null) {
                    $weathers[] = $weather;
                }
            }
        } else {
            $weathers[] = WeatherManager::getInstance()->getWeather($sender->getWorld());
        }

        if (isset($args[1]) && is_numeric($args[1])) {
            $duration = intval($args[1]);
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "WeatherUsage"));
            return;
        }
        switch ($type = strtolower($args[0])) {
            case "clear":
                foreach ($weathers as $weather) $weather->stopStorm();
                $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"WeatherClear"));
                break;
            case "query":
                $state = "clear";
                $weather = WeatherManager::getInstance()->getWeather($sender->getWorld());
                if ($weather->isRaining()) {
                    if ($weather->isThundering()) {
                        $state = "thunder";
                    } else {
                        $state = "rain";
                    }
                }
                $this->plugin->getScheduler()->cancelAllTasks();
                $sender->sendMessage($api->getCommandPrefix("Prefix") . str_replace("{status}", $state ,$api->getLang($sender->getName(),"WeatherClear")));
                return;
            case "rain":
                foreach ($weathers as $weather) $weather->startStorm(false, $duration);
                $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"WeatherRain"));
                return;
            case "thunder":
                foreach ($weathers as $weather) $weather->startStorm(true, $duration);
                $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(),"WeatherThunder"));
                return;
            default:
                $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "WeatherUsage"));
        }
    }
}