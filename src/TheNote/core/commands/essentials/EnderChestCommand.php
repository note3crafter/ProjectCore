<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\essentials;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class EnderChestCommand extends Command
{
    private $plugin;
    private $tName;
    private $inv;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("ec", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("EnderChestDescription"), "/ec", ["enderchest"]);
        $this->setPermission(Permissions::$enderchest);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        $this->tName = "";
        $tName = $sender->getName();
        $this->tName = "$tName";
        $sender->sendMessage($api->getCommandPrefix("Prefix") . $api->getLang($sender->getName(), "ECOpen"));
        $this->send($sender);
        return true;
    }

    public function send($sender)
    {
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $inv = $menu->getInventory();
        $menu->setName($this->tName . "'s Enderchest");
        $target = $this->plugin->getServer()->getPlayerExact($this->tName);
        $content = $target->getEnderInventory()->getContents();
        $this->inv = $menu;
        $inv->setContents($content);
        $menu->setListener(function (InvMenuTransaction $transaction) use ($sender): InvMenuTransactionResult {
            $inv = $this->inv->getInventory();
            $target = $this->plugin->getServer()->getPlayerExact($this->tName);
            if ($target->getName() !== $sender->getName()) {
                return $transaction->discard();
            } else {
                $nContents = $inv->getContents();
                $sender->getEnderInventory()->setContents($nContents);
                return $transaction->continue();
            }
        });
        $menu->setInventoryCloseListener(function (Player $sender, Inventory $inventory): void {
            if ($this->tName == $sender->getName()) {
                $nContents = $inventory->getContents();
                $sender->getEnderInventory()->setContents($nContents);
            }
        });
        $menu->send($sender);
    }
}