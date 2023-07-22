<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\other;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class SeePermsCommand extends Command
{
    private $plugin;
    private $pmDefaultPerms = [];

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("seeperms", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("SeePermsDescription"), "/seeperms [pluginname]", ["fperms"]);
        $this->setPermission(Permissions::$seeperms);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!isset($args[0]) || count($args) > 2) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "SeePermsUsage"));
            return true;
        }
        $plugin = (strtolower($args[0]) === 'pocketmine' || strtolower($args[0]) === 'pmmp') ? 'pocketmine' : $this->plugin->getServer()->getPluginManager()->getPlugin($args[0]);
        if ($plugin === null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . str_replace("{plugin}", $args[0], $api->getLang($sender->getName(), "SeePermsPluginNotExist")));
            return true;
        }
        $permissions = ($plugin instanceof PluginBase) ? $plugin->getDescription()->getPermissions() : $this->getPocketMinePerms();
        if (empty($permissions)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . str_replace("{plugin}", $plugin->getName(), $api->getLang($sender->getName(), "SeePermsPluginNoPermisson")));
            return true;
        }
        $pageHeight = $sender instanceof ConsoleCommandSender ? 48 : 6;
        $chunkedPermissions = array_chunk($permissions, $pageHeight);
        $maxPageNumber = count($chunkedPermissions);
        if(!isset($args[1]) || !is_numeric($args[1]) || $args[1] <= 0){
            $pageNumber = 1;
        }else if($args[1] > $maxPageNumber){
            $pageNumber = $maxPageNumber;
        }else{
            $pageNumber = $args[1];
        }
        $sender->sendMessage("§5Permissions from §e$args[0] §d" . $pageNumber . "§f/§d".   $maxPageNumber);
        foreach($chunkedPermissions[$pageNumber - 1][0] as $permission){
            $sender->sendMessage("§e->" . $permission->getName());
        }
        return true;

    }
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
    public function getPocketMinePerms()
    {
        if ($this->pmDefaultPerms === []) {
            foreach (PermissionManager::getInstance()->getPermissions() as $permission) {
                if (str_contains($permission->getName(), DefaultPermissions::ROOT_CONSOLE))
                    $this->pmDefaultPerms[] = $permission;
            }
        }
        return $this->pmDefaultPerms;
    }
}
