<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\listener;

use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Manager as SBM;

class ScoreBoardListner
{

    function numberPacket(Player $player, $score = 1, $msg = ""): void
    {
        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "standart";
        $entrie->type = 3;
        $entrie->customName = str_repeat("", 5) . $msg . str_repeat(" ", 1);
        $entrie->score = $score;
        $entrie->scoreboardId = $score;
        $pk = new SetScorePacket();
        $pk->type = 1;
        $pk->entries[] = $entrie;
        $player->getNetworkSession()->sendDataPacket($pk);
        $pk2 = new SetScorePacket();
        $pk2->entries[] = $entrie;
        $pk2->type = 0;
        $player->getNetworkSession()->sendDataPacket($pk2);
    }

    public function scoreboard(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {

            $sbconfig = new Config(Main::getInstance()->getDataFolder() . CoreAPI::$settings . "Scoreboard.yml", Config::YAML);

            $pk = new SetDisplayObjectivePacket();
            $pk->displaySlot = "sidebar";
            $pk->objectiveName = "standart";
            $pk->criteriaName = "dummy";
            $pk->sortOrder = 0;
            $pk->displayName = $sbconfig->get("title");
            $player->getNetworkSession()->sendDataPacket($pk);
            if ($sbconfig->get("l1") === true) {
                $cfg1 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line1"));
                $this->numberPacket($player, 1, $cfg1);
            }
            if ($sbconfig->get("l2") === true) {
                $cfg2 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line2"));
                $this->numberPacket($player, 2, $cfg2);
            }
            if ($sbconfig->get("l3") === true) {
                $cfg3 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line3"));
                $this->numberPacket($player, 3, $cfg3);
            }
            if ($sbconfig->get("l4") === true) {
                $cfg4 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line4"));
                $this->numberPacket($player, 4, $cfg4);
            }
            if ($sbconfig->get("l5") === true) {
                $cfg5 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line5"));
                $this->numberPacket($player, 5, $cfg5);
            }
            if ($sbconfig->get("l6") === true) {
                $cfg6 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line6"));
                $this->numberPacket($player, 6, $cfg6);
            }
            if ($sbconfig->get("l7") === true) {
                $cfg7 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line7"));
                $this->numberPacket($player, 7, $cfg7);
            }
            if ($sbconfig->get("l8") === true) {
                $cfg8 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line8"));
                $this->numberPacket($player, 8, $cfg8);
            }
            if ($sbconfig->get("l9") === true) {
                $cfg9 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line9"));
                $this->numberPacket($player, 9, $cfg9);
            }
            if ($sbconfig->get("l10") === true) {
                $cfg10 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line10"));
                $this->numberPacket($player, 10, $cfg10);
            }
            if ($sbconfig->get("l11") === true) {
                $cfg11 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line11"));
                $this->numberPacket($player, 11, $cfg11);
            }
            if ($sbconfig->get("l12") === true) {
                $cfg12 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line12"));
                $this->numberPacket($player, 12, $cfg12);
            }
            if ($sbconfig->get("l13") === true) {
                $cfg13 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line13"));
                $this->numberPacket($player, 13, $cfg13);
            }
            if ($sbconfig->get("l14") === true) {
                $cfg14 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line14"));
                $this->numberPacket($player, 14, $cfg14);
            }
            if ($sbconfig->get("l15") === true) {
                $cfg15 = SBM::formateString(Main::getInstance(), $player, $sbconfig->get("line15"));
                $this->numberPacket($player, 15, $cfg15);
            }
        }
    }
}