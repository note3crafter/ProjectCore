<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\listener;

use DateInterval;
use DateTime;
use Exception;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use TheNote\core\CoreAPI;
use TheNote\core\Main;

class BanListener implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    public function onLogin(PlayerJoinEvent $event)
    {
        $api = new CoreAPI();
        $player = $event->getPlayer();
        $name = $player->getName();
        if ($api->getBan($name,"ban") === true) {
            $player->hidePlayer($player);
            if ($api->getBan($name,"banid") === 99) {
                $banby = $api->getBan($name,"bannedby");
                $player->kick("Du wurdest Permanent Gebannt von : $banby\nEntbannungsantrag : https://bit.ly/discordcy");
                return true;
            }
            $date = $api->getBan($name,"bantime");
            try {
                $bantime = new DateTime($date);
            } catch (Exception $e) {
            }
            if (new DateTime("now") < $bantime) {
                $time = new DateTime("now");
                $tFormat = $time->format('Y:m:d:H:i:s');
                $zone = explode(":", $tFormat);
                try {
                    $bantime->sub(new DateInterval("P" . $zone[0] . "Y" . $zone[1] . "M" . $zone[2] . "DT" . $zone[3] . "H" . $zone[4] . "M" . $zone[5] . "S"));
                } catch (Exception $e) {
                }
                $bFormat = $bantime->format('m:d:H:i:s');
                $duration = explode(":", $bFormat);
                $month = $duration[0];
                $day = $duration[1];
                $hour = $duration[2];
                $minute = $duration[3];
                $second = $duration[4];
                $banids = $api->getBan($name,"banid");
                $banby = $api->getBan($name,"bannedby");
                if($banids == 1) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dCheating/Hacking\n§cFür§f: §e$month Monate und $day Tage!", false);
                } elseif ($banids == 2) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dVerstoß gegen die DSGVO\n§cFür§f: §e$day Tage und $hour Stunden!", false);
                } elseif ($banids == 3) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dRespektloses Verhalten\n§cFür§f: §e$hour Stunden und $minute Minuten!", false);
                } elseif ($banids == 4) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dProvokantes Verhalten\n§cFür§f: §e$minute Minuten und $second Sekunden!", false);
                } elseif ($banids == 5) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dSpamming\n§cFür§f: §e$minute Minuten und $second Sekunden!", false);
                } elseif ($banids == 6) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dWerbung\n§cFür§f: §e$day Tage und $hour Stunden!", false);
                } elseif ($banids == 7) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dReport Missbrauch\n§cFür§f: §e$minute Minuten und $second Sekunden!", false);
                } elseif ($banids == 8) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dWortwahl/Drohung\n§cFür§f: §e$day Tage und $hour Stunden!", false);
                } elseif ($banids == 9) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dUnnötiges Gesülze im Chat\n§cFür§f: §e$minute §cMinuten!", false);
                } elseif ($banids == 10) {
                    $player->kick("§cDu wurdest Gebannt! §dVon§f: §e$banby\n§cGrund§f: §dAußnutzung von Bugs\n§cFür§f: §e$hour Stunden und $minute Minuten!", false);
                }
            }
        }
        return true;
    }
}