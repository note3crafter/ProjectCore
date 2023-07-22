<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\other;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\utils\Permissions;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\formapi\SimpleForm;

class StatsCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("stats", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("StatsDescription"), "/stats");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    break;
            }
        });
        $form->setTitle($api->getCommandPrefix("UIName"));
        $form->setContent("§6======§f[§eStats§f]§6======\n" .
            "§eDeine Statistiken\n" .
            "Your Joins : " . $api->getJoinPoints($sender->getName()) . "\n" .
            "Your Jumps : " . $api->getJumpPoints($sender->getName()) . "\n" .
            "Your Kicks : " . $api->getKickPoints($sender->getName()) . "\n" .
            "Your Interacts : " . $api->getInteractPoints($sender->getName()) . "\n" .
            "Walking Meters : " . round($api->getWalkPoints($sender->getName())) . "m\n" .
            "Flying Metesr : " . round($api->getFlyPoints($sender->getName())) . "m\n" .
            "Blocks break : " . $api->getBreakPoints($sender->getName()) . "\n" .
            "Blocks placed : " . $api->getPlacePoints($sender->getName()) . "\n" .
            "Dropped Items : " . $api->getDropPoints($sender->getName()) . "\n" .
            "Collecting Items : " . $api->getPickPoints($sender->getName()) . "\n" .
            "Consumed Items : " . $api->getConsumePoints($sender->getName()) . "\n" .
            "Your Deaths : " . $api->getDeathPoints($sender->getName()) . "\n" .
            "Your Kills : " . $api->getKillPoints($sender->getName()) . "\n" .
            "Your Messages : " . $api->getMessagePoints($sender->getName()) . "\n".
            "Your Votes : " . $api->getVotePoints($sender->getName()));

        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}