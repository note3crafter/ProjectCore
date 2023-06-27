<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core\commands\groupsystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\Main;
use TheNote\core\utils\Permissions;

class GroupCommand extends Command
{
    public $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("group", $api->getCommandPrefix("Prefix") . $api->getCommandPrefix("GroupDescription"), "/group", ["gruppe"]);
        $this->setPermission(Permissions::$group);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $groups = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . CoreAPI::$cloud . "players.yml", Config::YAML);
        $api = new CoreAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage("§f=========== " . $api->getCommandPrefix("Group") . "§f===========");
            $sender->sendMessage("§6/group add {groupname}");
            $sender->sendMessage("§6/group list");
            $sender->sendMessage("§6/group remove {groupname}");
            $sender->sendMessage("§6/group addperm {groupname} {permission}");
            $sender->sendMessage("§6/group removeperm {groupname} {permission}");
            $sender->sendMessage("§6/group default {groupname}");
            $sender->sendMessage("§6/group set {player} {groupname}");
            $sender->sendMessage("§6/group adduserperm {player} {permission}");
            $sender->sendMessage("§6/group removeuserperm {player} {permission}");
            $sender->sendMessage("§6/group listgroupperm {groupname}");
            $sender->sendMessage("§6/group listuserperm {groupname}");
            return false;
        }
        if ($sender->hasPermission("core.command.group")) {
            if ($args[0] == "add") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "GroupUsageAdd"));
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) !== null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "GroupAddError"));
                    return false;
                }
                $groups->setNested("Groups." . $groupName . ".groupprefix", $groupName);
                $groups->setNested("Groups." . $groupName . ".format1", "[$groupName] : {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".format2", "[$groupName] : {clan} {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".format3", "[$groupName] : {heirat} {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".format4", "[$groupName] : {heirat} {clan} {name} | {msg}");
                $groups->setNested("Groups." . $groupName . ".nametag", "$groupName §7: §8{name}");
                $groups->setNested("Groups." . $groupName . ".displayname", "$groupName §7: §8{name}");
                $groups->setNested("Groups." . $groupName . ".permissions", ["CoreV5"]);
                $groups->save();
                $message = str_replace("{group}" , $groupName, $api->getLang($sender->getName(),"GroupAddSucces"));
                $sender->sendMessage($api->getCommandPrefix("Group") . $message);
            }
            if ($args[0] == "list") {
                $list = [];
                $grouplist = $groups->get("Groups");
                foreach ($grouplist as $name => $data) $list[] = $name;
                $sender->sendMessage($api->getCommandPrefix("Group") . "\n§8- §7" . implode("\n§8-§7 ", $list));
            }
            if ($args[0] == "remove") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"GroupUsageRemove"));
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"GroupError"));
                    return true;
                }
                $groups->removeNested("Groups." . $groupName);
                $groups->save();
                $message = str_replace("{group}" , $groupName, $api->getLang($sender->getName(),"GroupRemoveSucces"));
                $sender->sendMessage($api->getCommandPrefix("Group") . $message);
            }
            if ($args[0] == "addperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"GroupAddpermUsage"));
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"GroupError"));
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions", []);
                $permission = $args[2];
                $perms[] = $permission;
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();
                $message = str_replace("{group}" , $args[1], $api->getLang($sender->getName(),"GroupAddpermSucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getCommandPrefix("Group") . $message1);
            }
            if ($args[0] == "removeperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "GroupRemovePermUsage"));
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"GroupError"));
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions", []);
                $permission = $args[2];
                if (!in_array($permission, $perms)) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"GroupRemovePermError"));
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();

                $message = str_replace("{group}" , $args[1], $api->getLang($sender->getName(),"GroupRemovePermSucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getCommandPrefix("Group") . $message1);
            }
            if ($args[0] == "default") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "GroupDefaultUsage"));
                    return true;
                }
                if ($groups->getNested("Groups." . $args[1]) == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"GroupError"));
                    return true;
                }
                $groups->set("DefaultGroup", $args[1]);
                $groups->save();
                $message = str_replace("{group}" , $args[1], $api->getLang($sender->getName(),"GroupDefaultSucces"));
                $sender->sendMessage($api->getCommandPrefix("Group") . $message);
            }
            if ($args[0] == "set") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "GroupSetUsage"));
                    return false;
                }
                if (empty($args[2])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(), "GroupSetUsage"));
                    return false;
                }
                $target = $api->findPlayer($sender, $args[1]);
                if ($target == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
                    return false;
                }
                $name = $target->getName();
                $group = $args[2];
                if ($groups->getNested("Groups." . $group) == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"GroupError"));
                    return false;
                }
                $groupprefix = $groups->getNested("Groups." . $group .".groupprefix");
                $playerdata->setNested($name . ".groupprefix", $groupprefix );
                $playerdata->setNested($name . ".group", $group);
                $playerdata->save();

                $playergroup = $playerdata->getNested($name.".group");
                $nametag = str_replace("{name}", $target->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
                $displayname = str_replace("{name}", $target->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
                $target->setNameTag($nametag);
                $target->setDisplayName($displayname);

                $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
                foreach($permissionlist as $name => $data) {
                    $target->addAttachment($this->plugin)->setPermission($data, true);
                }
                //$target->kick($api->getCommandPrefix("Group") . "§6Deine Gruppe wurde zu : $group §6geändert!\n§6Rejoine einfach den Server!", false);
                $message = str_replace("{group}" , $group, $api->getLang($sender->getName(),"GroupSetSucces"));
                $message1 = str_replace("{player}" , $target->getName(), $message);
                $sender->sendMessage($api->getCommandPrefix("Group") . $message1);
            }
            if ($args[0] == "adduserperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"GroupAddUserPermUsage"));
                    return false;
                }
                if (empty($args[2])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"GroupAddUserPermUsage"));
                    return false;
                }
                $target = $api->findPlayer($sender, $args[1]);
                if ($target == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
                    return false;
                }
                $spieler = $args[1];
                $permission = $args[2];
                $perms = $playerdata->getNested("$spieler.permissions", []);
                $perms[] = $permission;
                $playerdata->setNested("$spieler.permissions", $perms);
                $playerdata->save();

                $message = str_replace("{player}" , $args[1], $api->getLang($sender->getName(),"GroupAddUserPermSucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getCommandPrefix("Group") . $message1);
            }
            if ($args[0] == "removeuserperm") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"GroupRemoveUserPermUsage"));
                    return false;
                }
                if (empty($args[2])) {
                    $sender->sendMessage($api->getCommandPrefix("Info") . $api->getLang($sender->getName(),"GroupRemoveUserPermUsage"));
                    return false;
                }
                $target = $api->findPlayer($sender, $args[1]);
                if ($target == null) {
                    $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
                    return false;
                }

                $spieler = $args[1];
                $permission = $args[2];
                $perms = $playerdata->getNested("$spieler.permissions", []);
                if (!in_array($permission, $perms)) {
                    $sender->sendMessage($api->getCommandPrefix("error") . $api->getLang($sender->getName(),"GroupRemoveUserPermError"));
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $playerdata->setNested("$spieler.permissions", $perms);
                $playerdata->save();

                $message = str_replace("{player}" , $args[1], $api->getLang($sender->getName(),"GroupRemoveUserPermSucces"));
                $message1 = str_replace("{perm}" , $args[2], $message);
                $sender->sendMessage($api->getCommandPrefix("Group") . $message1);
            }
            if ($args[0] == "listperms") {
                $sender->sendMessage("Comming Soon...");
            }
            if ($args[0] == "listuserperms") {
                $sender->sendMessage("Comming Soon...");
            }
        }
        return true;
    }
}