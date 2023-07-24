<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\core;

use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\commands\CreditsCommand;
use TheNote\core\commands\essentials\AFKCommand;
use TheNote\core\commands\essentials\BackCommand;
use TheNote\core\commands\essentials\BurnCommand;
use TheNote\core\commands\essentials\ChatClearCommand;
use TheNote\core\commands\essentials\ClearCommand;
use TheNote\core\commands\essentials\DayCommand;
use TheNote\core\commands\essentials\EnderChestCommand;
use TheNote\core\commands\essentials\ExtinguishCommand;
use TheNote\core\commands\essentials\FeedCommand;
use TheNote\core\commands\essentials\FlyCommand;
use TheNote\core\commands\essentials\GodModeCommand;
use TheNote\core\commands\essentials\HealCommand;
use TheNote\core\commands\essentials\ItemIDCommand;
use TheNote\core\commands\essentials\KickallCommand;
use TheNote\core\commands\essentials\KickCommand;
use TheNote\core\commands\essentials\MilkCommand;
use TheNote\core\commands\essentials\NearCommand;
use TheNote\core\commands\essentials\NickCommand;
use TheNote\core\commands\essentials\NightCommand;
use TheNote\core\commands\essentials\NukeCommand;
use TheNote\core\commands\essentials\PosCommand;
use TheNote\core\commands\essentials\RealnameCommand;
use TheNote\core\commands\essentials\RenameCommand;
use TheNote\core\commands\essentials\RepairCommand;
use TheNote\core\commands\essentials\SignCommand;
use TheNote\core\commands\essentials\SizeCommand;
use TheNote\core\commands\essentials\SpeedCommand;
use TheNote\core\commands\essentials\SudoCommand;
use TheNote\core\commands\essentials\TopCommand;
use TheNote\core\commands\essentials\TpallCommand;
use TheNote\core\commands\essentials\UnnickCommand;
use TheNote\core\commands\essentials\VanishCommand;
use TheNote\core\commands\gamemode\AdventureCommand;
use TheNote\core\commands\gamemode\CreativeCommand;
use TheNote\core\commands\gamemode\SpectatorCommand;
use TheNote\core\commands\gamemode\SurvivalCommand;
use TheNote\core\commands\groupsystem\GroupCommand;
use TheNote\core\commands\misc\AdminItemsCommand;
use TheNote\core\commands\misc\LightningCommand;
use TheNote\core\commands\msgsystem\NoDMCommand;
use TheNote\core\commands\msgsystem\ReplyCommand;
use TheNote\core\commands\msgsystem\TellCommand;
use TheNote\core\commands\other\ClearlaggCommand;
use TheNote\core\commands\other\HubCommand;
use TheNote\core\commands\other\LangCommand;
use TheNote\core\commands\other\ScoreBoardCommand;
use TheNote\core\commands\other\SeePermsCommand;
use TheNote\core\commands\other\ServerStatsCommand;
use TheNote\core\commands\other\SetHubCommand;
use TheNote\core\commands\other\StatsCommand;
use TheNote\core\commands\tpasystem\TpaacceptCommand;
use TheNote\core\commands\tpasystem\TpaCommand;
use TheNote\core\commands\tpasystem\TpadenyCommand;
use TheNote\core\commands\tpasystem\TpahereCommand;
use TheNote\core\commands\VersionCommand;
use TheNote\core\commands\warpsystem\DelWarpCommand;
use TheNote\core\commands\warpsystem\ListWarpCommand;
use TheNote\core\commands\warpsystem\SetWarpCommand;
use TheNote\core\commands\warpsystem\WarpCommand;
use TheNote\core\commands\weather\ToggleDownFallCommand;
use TheNote\core\commands\weather\WeatherCommand;
use TheNote\core\commands\bansystem\BanCommand;
use TheNote\core\commands\bansystem\UnbanCommand;
use TheNote\core\commands\economy\GiveMoneyCommand;
use TheNote\core\commands\economy\MyMoneyCommand;
use TheNote\core\commands\economy\PayallCommand;
use TheNote\core\commands\economy\PayMoneyCommand;
use TheNote\core\commands\economy\SeeMoneyCommand;
use TheNote\core\commands\economy\SetMoneyCommand;
use TheNote\core\commands\economy\TakeMoneyCommand;
use TheNote\core\commands\economy\TopMoneyCommand;
use TheNote\core\commands\homesystem\DelHomeCommand;
use TheNote\core\commands\homesystem\HomeCommand;
use TheNote\core\commands\homesystem\ListHomeCommand;
use TheNote\core\commands\homesystem\SetHomeCommand;
use TheNote\core\events\BlockBreak;
use TheNote\core\events\BlockPlace;
use TheNote\core\events\PlayerChat;
use TheNote\core\events\PlayerConsume;
use TheNote\core\events\PlayerDeath;
use TheNote\core\events\PlayerDrop;
use TheNote\core\events\PlayerInteract;
use TheNote\core\events\PlayerJoin;
use TheNote\core\events\PlayerJump;
use TheNote\core\events\PlayerKick;
use TheNote\core\events\PlayerMove;
use TheNote\core\events\PlayerPick;
use TheNote\core\events\PlayerQuit;
use TheNote\core\events\PlayerToggleSneak;
use TheNote\core\events\SpawnProtection;
use TheNote\core\listener\BackListener;
use TheNote\core\listener\BanListener;
use TheNote\core\listener\CoreListner;
use TheNote\core\listener\GroupListener;
use TheNote\core\task\ParticleTask;
use TheNote\core\utils\ConfigChecker;
use TheNote\core\utils\DiscordAPI;
use TheNote\core\utils\GroupsGenerate;
use TheNote\core\world\WorldManager;

class Main extends PluginBase
{
    public static string $defaultperm = "ProjectCore";
    public static string $version = "1.1.0";
    public static string $dateversion = "22.07.2023";
    public static string $mcpeversion = "1.20.12";
    public static string $protokoll = "594";
    public static string $plname = "ProjectCore";


    public static $instance;
    public static $godmod = [];
    public static $vanish= [];
    public static $afksesion = [];
    /**
     * @var array|mixed
     */
    private WorldManager $worldManager;
    public static $cooldown = [];
    public static function getInstance()
    {
        return self::$instance;
    }
    public function onLoad(): void {
        self::$instance = $this;
        @mkdir($this->getDataFolder() . "Settings");
        @mkdir($this->getDataFolder() . "Cloud");
        @mkdir($this->getDataFolder() . "Lang");
        @mkdir($this->getDataFolder() . "Cloud/players/");
        @mkdir($this->getDataFolder() . "Cloud/players/Ban/");
        @mkdir($this->getDataFolder() . "Cloud/players/User/");
        @mkdir($this->getDataFolder() . "Cloud/players/Logdata/");
        @mkdir($this->getDataFolder() . "Cloud/players/Group/");
        @mkdir($this->getDataFolder() . "Cloud/players/Marry/");
        @mkdir($this->getDataFolder() . "Cloud/players/Friends/");
        @mkdir($this->getDataFolder() . "Cloud/players/Clans");
        @mkdir($this->getDataFolder() . "Cloud/players/Homes");
        @mkdir($this->getDataFolder() . "Cloud/players/Stats");
        @mkdir($this->getDataFolder() . "Cloud/players/Scoreboard");
        $this->saveResource("Settings/Config.yml");
        $this->saveResource("Settings/StarterKit.yml");
        $this->saveResource("Settings/Modules.yml");
        $this->saveResource("Settings/Scoreboard.yml");
        $this->saveResource("Settings/Discord.yml");
        $this->saveResource("Lang/LangCommandPrefix.yml");
        $this->saveResource("Lang/LangDEU.json");
        $this->saveResource("Lang/LangENG.json");
        $this->saveResource("Lang/LangESP.json");

        $g = new GroupsGenerate();
        $g->groupsgenerate();
        //$c = new ConfigChecker(); #Comming Soon
        //$c->cfgcheck();
        $capi = new CoreAPI();
        if ($capi->modules("WeatherSystem") === true) {
            $this->worldManager = new WorldManager();
        }
    }
    public function onEnable(): void
    {
        $this->banner();
        $capi = new CoreAPI();
        $clis = new CoreListner();
        foreach (scandir($this->getServer()->getDataPath() . "worlds") as $file) {
            if (Server::getInstance()->getWorldManager()->isWorldGenerated($file)) {
                $this->getServer()->getWorldManager()->loadWorld($file);

            }
        }
        if($capi->getConfig("NetWorkName") === true) {
            $this->getServer()->getNetwork()->setName($capi->getConfig("ServerName"));
        }

        if($capi->getConfig("JavaSneak") === true) {
            $this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, function (DataPacketSendEvent $event): void {
                foreach ($event->getPackets() as $packet) {
                    if ($packet instanceof StartGamePacket) {
                        $packet->levelSettings->experiments = new Experiments(array_merge($packet->levelSettings->experiments->getExperiments(), [
                            "short_sneaking" => true
                        ]), true);
                    }
                }
            }, EventPriority::HIGHEST, $this);
            $this->getServer()->getPluginManager()->registerEvent(PlayerToggleSneakEvent::class, function (PlayerToggleSneakEvent $event): void {
                $player = $event->getPlayer();
                if (!$event->isSneaking()) {
                    (new \ReflectionMethod($player, "recalculateSize"))->invoke($player);
                } elseif (!$player->isSwimming() && !$player->isGliding()) {
                    (new \ReflectionProperty($player->size, "height"))->setValue($player->size, 1.5 * $player->getScale());
                    (new \ReflectionProperty($player->size, "eyeHeight"))->setValue($player->size, 1.32 * $player->getScale());
                }
            }, EventPriority::MONITOR, $this);
        }
        if ($capi->modules("BanSystem") === true) {
            Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("ban"));
            Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("unban"));
            $this->getServer()->getCommandMap()->register("ban", new BanCommand($this));
            $this->getServer()->getCommandMap()->register("unban", new UnbanCommand($this));
            $this->getServer()->getPluginManager()->registerEvents(new BanListener($this), $this);

        }
        if ($capi->modules("EconomySystem") === true) {
            if ($capi->modules("GiveMoney") === true) {
                $this->getServer()->getCommandMap()->register("givemoney", new GiveMoneyCommand($this));
            }
            if ($capi->modules("MyMoney") === true) {
                $this->getServer()->getCommandMap()->register("mymoney", new MyMoneyCommand($this));
            }
            if ($capi->modules("PayAll") === true) {
                $this->getServer()->getCommandMap()->register("payall", new PayallCommand($this));
            }
            if ($capi->modules("PayMoney") === true) {
                $this->getServer()->getCommandMap()->register("pay", new PayMoneyCommand($this));
            }
            if ($capi->modules("SeeMoney") === true) {
                $this->getServer()->getCommandMap()->register("seemoney", new SeeMoneyCommand($this));
            }
            if ($capi->modules("SetMoney") === true) {
                $this->getServer()->getCommandMap()->register("setmoney", new SetMoneyCommand($this));
            }
            if ($capi->modules("TakeMoney") === true) {
                $this->getServer()->getCommandMap()->register("takemoney", new TakeMoneyCommand($this));
            }
            if ($capi->modules("TopMoney") === true) {
                $this->getServer()->getCommandMap()->register("topmoney", new TopMoneyCommand($this));
            }
        }
        if ($capi->modules("Essentials") === true) {
            if ($capi->modules("AFK") === true) {
                $this->getServer()->getCommandMap()->register("afk", new AFKCommand($this));
            }
            if ($capi->modules("Back") === true) {
                $this->getServer()->getCommandMap()->register("back", new BackCommand($this));
                $this->getServer()->getPluginManager()->registerEvents(new BackListener($this), $this);
            }
            if ($capi->modules("Burn") === true) {
                $this->getServer()->getCommandMap()->register("burn", new BurnCommand($this));
            }
            if ($capi->modules("ChatClear") === true) {
                $this->getServer()->getCommandMap()->register("chatclear", new ChatClearCommand($this));
            }
            if ($capi->modules("Clear") === true) {
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("clear"));
                $this->getServer()->getCommandMap()->register("clear", new ClearCommand($this));
            }
            if ($capi->modules("Day") === true) {
                $this->getServer()->getCommandMap()->register("day", new DayCommand($this));
            }
            if ($capi->modules("Heal") === true) {
                $this->getServer()->getCommandMap()->register("heal", new HealCommand($this));
            }
            if ($capi->modules("EnderChest") === true) {
                $invmenu = $this->getServer()->getPluginManager()->getPlugin("InvMenu");
                if ($invmenu === null) {
                    $this->getLogger()->alert("Please install InvMenu to use the EnderChest Command!");
                } else {
                    $this->getServer()->getCommandMap()->register("enderchest", new EnderChestCommand($this));
                }
            }
            if ($capi->modules("Extinguish") === true) {
                $this->getServer()->getCommandMap()->register("extinguish", new ExtinguishCommand($this));
            }
            if ($capi->modules("Feed") === true) {
                $this->getServer()->getCommandMap()->register("feed", new FeedCommand($this));
            }
            if ($capi->modules("Fly") === true) {
                $this->getServer()->getCommandMap()->register("fly", new FlyCommand($this));
            }
            if ($capi->modules("Godmode") === true) {
                $this->getServer()->getCommandMap()->register("godmode", new GodModeCommand($this));
            }
            if ($capi->modules("ID") === true) {
                $this->getServer()->getCommandMap()->register("back", new ItemIDCommand($this));
            }
            if ($capi->modules("Kick") === true) {
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("kick"));
                $this->getServer()->getCommandMap()->register("kick", new KickCommand($this));
            }
            if ($capi->modules("Kickall") === true) {
                $this->getServer()->getCommandMap()->register("kickall", new KickallCommand($this));
            }
            if ($capi->modules("Milk") === true) {
                $this->getServer()->getCommandMap()->register("milk", new MilkCommand($this));
            }
            if ($capi->modules("Nick") === true) {
                if ($capi->modules("GroupSystem") === true) {
                    $this->getServer()->getCommandMap()->register("nick", new NickCommand($this));
                } else {
                    $this->getLogger()->info("§ePlease activate the GroupSystem to use the Command /nick");
                }
                $this->getServer()->getCommandMap()->register("nick", new NickCommand($this));
            }
            if ($capi->modules("Night") === true) {
                $this->getServer()->getCommandMap()->register("night", new NightCommand($this));
            }
            if ($capi->modules("Near") === true) {
                $this->getServer()->getCommandMap()->register("near", new NearCommand($this));
            }
            if ($capi->modules("Nuke") === true) {
                $this->getServer()->getCommandMap()->register("nuke", new NukeCommand($this));
            }
            if ($capi->modules("Position") === true) {
                $this->getServer()->getCommandMap()->register("pos", new PosCommand($this));
            }
            if ($capi->modules("Realname") === true) {
                $this->getServer()->getCommandMap()->register("back", new RealnameCommand($this));
            }
            if ($capi->modules("Repair") === true) {
                $this->getServer()->getCommandMap()->register("repair", new RepairCommand($this));
            }
            if ($capi->modules("Rename") === true) {
                $this->getServer()->getCommandMap()->register("rename", new RenameCommand($this));
            }
            if ($capi->modules("Sign") === true) {
                $this->getServer()->getCommandMap()->register("sign", new SignCommand($this));
            }
            if ($capi->modules("Size") === true) {
                $this->getServer()->getCommandMap()->register("size", new SizeCommand($this));
            }
            if ($capi->modules("Speed") === true) {
                $this->getServer()->getCommandMap()->register("back", new SpeedCommand($this));
            }
            if ($capi->modules("Sudo") === true) {
                $this->getServer()->getCommandMap()->register("sudo", new SudoCommand($this));
            }
            if ($capi->modules("Top") === true) {
                $this->getServer()->getCommandMap()->register("top", new TopCommand($this));
            }
            if ($capi->modules("TPAll") === true) {
                $this->getServer()->getCommandMap()->register("tpall", new TpallCommand($this));
            }
            if ($capi->modules("Unnick") === true) {
                if ($capi->modules("GroupSystem") === true) {
                    $this->getServer()->getCommandMap()->register("unnick", new UnnickCommand($this));
                } else {
                    $this->getLogger()->info("§ePlease activate the GroupSystem to use the Command /unnick");
                }
            }
            if ($capi->modules("Vanish") === true) {
                $this->getServer()->getCommandMap()->register("vanish", new VanishCommand($this));
            }
        }
        if ($capi->modules("GamemodeSystem") === true) {
            if ($capi->modules("Advendture") === true) {
                $this->getServer()->getCommandMap()->register("gma", new AdventureCommand($this));
            }
            if ($capi->modules("Creative") === true) {
                $this->getServer()->getCommandMap()->register("gmc", new CreativeCommand($this));
            }
            if ($capi->modules("Spectator") === true) {
                $this->getServer()->getCommandMap()->register("gmspc", new SpectatorCommand($this));
            }
            if ($capi->modules("Survival") === true) {
                $this->getServer()->getCommandMap()->register("gms", new SurvivalCommand($this));
            }
        }
        if ($capi->modules("GroupSystem") === true) {
            $this->getServer()->getCommandMap()->register("group", new GroupCommand($this));
            $this->getServer()->getPluginManager()->registerEvents(new GroupListener($this), $this);
        }
        if ($capi->modules("HomeSystem") === true) {
            $this->getServer()->getCommandMap()->register("delhome", new DelHomeCommand($this));
            $this->getServer()->getCommandMap()->register("home", new HomeCommand($this));
            $this->getServer()->getCommandMap()->register("listhome", new ListHomeCommand($this));
            $this->getServer()->getCommandMap()->register("sethome", new SetHomeCommand($this));
        }
        if ($capi->modules("Misc") === true) {
            if($capi->modules("AdminItems") === true) {
                $this->getServer()->getCommandMap()->register("adminitems", new AdminItemsCommand($this));
            }
            if ($capi->modules("LightningCommand") === true) {
                $this->getServer()->getCommandMap()->register("lightning", new LightningCommand($this));
            }
        }
        if ($capi->modules("MSGSystem") === true) {
            if ($capi->modules("NoMSG") === true) {
                $this->getServer()->getCommandMap()->register("nodm", new NoDMCommand($this));
            }
            if ($capi->modules("Tell") === true) {
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("tell"));
                $this->getServer()->getCommandMap()->register("reply", new ReplyCommand($this));
                $this->getServer()->getCommandMap()->register("tell", new TellCommand($this));
            }
        }
        if ($capi->modules("Otherthings") === true) {
            if ($capi->modules("ClearLagg") === true) {
                $this->getServer()->getCommandMap()->register("clearlagg", new ClearlaggCommand($this));
            }
            if ($capi->modules("Hub") === true) {
                $this->getServer()->getCommandMap()->register("hub", new HubCommand($this));
                $this->getServer()->getCommandMap()->register("sethub", new SetHubCommand($this));
            }
            if ($capi->modules("SeePerms") === true) {
                $this->getServer()->getCommandMap()->register("seeperms", new SeePermsCommand($this));
            }
            if ($capi->modules("ScoreBoardSystem") === true) {
                $this->getServer()->getCommandMap()->register("sb", new ScoreBoardCommand($this));
            }
            if($capi->modules("StatsSystem") === true) {
                $this->getServer()->getCommandMap()->register("stats", new StatsCommand($this));
                $this->getServer()->getCommandMap()->register("serverstats", new ServerStatsCommand($this));
            }
        }
        if ($capi->modules("TPASystem") === true) {
            $this->getServer()->getCommandMap()->register("tpa", new TpaCommand($this));
            $this->getServer()->getCommandMap()->register("tpaccept", new TpaacceptCommand($this));
            $this->getServer()->getCommandMap()->register("tpadeny", new TpadenyCommand($this));
            $this->getServer()->getCommandMap()->register("tpahere", new TpahereCommand($this));
        }
        if ($capi->modules("WarpSystem") === true) {
            $this->getServer()->getCommandMap()->register("warp", new WarpCommand($this));
            $this->getServer()->getCommandMap()->register("setwarp", new SetWarpCommand($this));
            $this->getServer()->getCommandMap()->register("delwarp", new DelWarpCommand($this));
            $this->getServer()->getCommandMap()->register("listwarp", new ListWarpCommand($this));
        }
        if ($capi->modules("WeatherSystem") === true) {
            $this->getServer()->getCommandMap()->register("weather", new WeatherCommand($this));
            $this->getServer()->getCommandMap()->register("toggledownfall", new ToggleDownFallCommand($this));
        }
        $this->getServer()->getCommandMap()->register("credits", new CreditsCommand($this));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("version"));
        $this->getServer()->getCommandMap()->register("lang", new LangCommand($this));
        $this->getServer()->getCommandMap()->register("version", new VersionCommand($this));
        //Task
        if($capi->modules("Particle") === true) {
            $this->getScheduler()->scheduleRepeatingTask(new ParticleTask($this), 10);
        }
        //Events
        $this->getServer()->getPluginManager()->registerEvents(new BlockBreak($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BlockPlace($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerChat($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerConsume($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDeath($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDrop($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerInteract($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoin($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJump($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerKick($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerMove($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerPick($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerQuit($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerToggleSneak($this), $this);
        if($capi->getConfig("SpawnProtection") === true) {
            $this->getServer()->getPluginManager()->registerEvents(new SpawnProtection($capi->getConfig("Radius")), $this);
        }
        if($capi->modules("DiscordSystem") === true) {
            if($capi->getDiscord("Start") === true) {
                $dc = new DiscordAPI();
                $dc->sendMessage($capi->getDiscord("chatprefix"), $capi->getDiscord("StartMSG"));
            }
        }
    }
    public function onDisable(): void
    {
        $api = new CoreAPI();
        if ($api->getConfig("Rejoin") === true) {
            foreach ($this->getServer()->getOnlinePlayers() as $player) {
                $player->transfer($api->getConfig("IP"), $api->getConfig("Port"));
            }
        }
        if($api->modules("DiscordSystem") === true) {
            if($api->getDiscord("Stop") === true) {
                $dc = new DiscordAPI();
                $dc->sendMessage($api->getDiscord("chatprefix"), $api->getDiscord("StopMSG"));
            }
        }
    }

    private function Banner()
    {
        $banner = strval(
            "\n" .
            "╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗\n" .
            "║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝\n" .
            "║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗ \n" .
            "║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝ \n" .
            "║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗\n" .
            "╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝\n" .
            "Easy to Use! Written in Love! Project Core by TheNote/RetroRolf/Rudolf2000/note3crafter\n" .
            "                                         2017-2023                                       "
        );
        $this->getLogger()->info($banner);
    }
}