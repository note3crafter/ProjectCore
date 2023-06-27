<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\bansystem;

use DateInterval;
use DateTime;
use Exception;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use TheNote\core\utils\Permissions;

class BanCommand extends Command
{

    private $plugin;
    public $bans = array(
        1 => ['Reason' => 'Hacking', 'Duration' => '0:12:M'],
        2 => ['Reason' => 'Privatdaten', 'Duration' => '0:7:D'],
        3 => ['Reason' => 'Respektloses Verhalten', 'Duration' => '0:1:D'],
        4 => ['Reason' => 'Provokantes Verhalten', 'Duration' => 'T:1:H'],
        5 => ['Reason' => 'Spamming', 'Duration' => 'T:1:H'],
        6 => ['Reason' => 'Werbung', 'Duration' => '0:3:D'],
        7 => ['Reason' => 'Report Missbrauch', 'Duration' => 'T:1:H'],
        8 => ['Reason' => 'Wortwahl / Drohung', 'Duration' => '0:14:D'],
        9 => ['Reason' => 'Unnötiges Gesülze im Chat', 'Duration' => 'T:1:H'],
        10 => ['Reason' => 'Bugusing', 'Duration' => '0:1:D']
    );

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("ban", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("BanDescription"), "/ban", ["pun"]);
        $this->setPermission(Permissions::$ban);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "BanUsage"));
            return false;
        }
        if ($args[0] == "id") {
            $sender->sendMessage($api->getCommandPrefix("Ban") .  $api->getLang($sender->getName(), "BanID"));
            return true;
        }
        if ($args[0] == "help") {
            $sender->sendMessage($api->getCommandPrefix("Ban") .  $api->getLang($sender->getName(), "BanHelp"));
            return true;
        }
        if (isset($args[0])) {
            $victim = $api->findPlayer($sender, $args[0]);
            if($victim === null) return false;
            $vname = $victim->getName();
            if (empty($args[1])) {
                if ($api->findPlayer($sender, $args[0]) instanceof Player) {
                    $victim->kick($api->getLang($vname, "BanPerma"), false);
                    $api->setBan($victim, "bannedby", $sender->getName());
                    $api->setBan($victim, "banreason", "Permaban");
                    $api->setBan($victim, "banid", 99);
                    $api->setBan($victim, "ban", true);
                    return true;
                }
            } else {
                if (array_key_exists($args[1], $this->bans)) {
                    $idList = $this->bans[$args[1]];
                    $duration = explode(':', $idList['Duration']);
                    $date = new DateTime('now');
                    if ($duration[0] == 'T') {

                        try {
                            $date->add(new DateInterval('PT' . $duration[1] * "1" . $duration[2]));
                        } catch (Exception $e) {
                        }
                    } else {
                        try {
                            $date->add(new DateInterval('P' . $duration[1] * "1" . $duration[2]));
                        } catch (Exception $e) {
                        }
                    }
                    $format = $date->format('Y-m-d H:i:s');
                    if ($args[1] == 1) { //1 Jahr
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick1"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Hacking");
                        $api->setBan($victim, "banid", 1);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 2) { //1 Woche
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick2"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Privatdaten");
                        $api->setBan($victim, "banid", 2);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 3) { //1 Tag
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick3"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Respektloses Verhalten");
                        $api->setBan($victim, "banid", 3);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 4) { //1 Stunde
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick4"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Provokantes Verhalten");
                        $api->setBan($victim, "banid", 4);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 5) { //1 Stunde
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick5"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Spamming");
                        $api->setBan($victim, "banid", 5);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 6) { //3 Tage
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick6"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Werbung");
                        $api->setBan($victim, "banid", 6);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 7) { //1 Stunde
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick7"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Report Missbrauch");
                        $api->setBan($victim, "banid", 7);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 8) { //2 Wochen
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick8"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Wortwahl/Drohung");
                        $api->setBan($victim, "banid", 8);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 9) { //1 Tag
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick9"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Unnötiges Gesülze im Chat");
                        $api->setBan($victim, "banid", 9);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    } elseif ($args[1] == 10) { //1 Tag
                        $victim->kick($api->getCommandPrefix("Ban") . $api->getLang($vname, "BanKick10"), false);
                        $api->setBan($victim, "bannedby", $sender->getName());
                        $api->setBan($victim, "banreason", "Bugusing");
                        $api->setBan($victim, "banid", 10);
                        $api->setBan($victim, "bantime", $format);
                        $api->setBan($victim, "ban", true);
                        return true;
                    }
                } else {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "BanErrorID"));
                    return false;
                }
            }
        }
        return true;
    }
}