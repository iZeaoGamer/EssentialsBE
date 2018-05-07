<?php

declare(strict_types = 1);

namespace EssentialsBE;

use EssentialsBE\BaseFiles\BaseAPI;
use EssentialsBE\BaseFiles\BaseCommand;
use EssentialsBE\Commands\AFK;
use EssentialsBE\Commands\Antioch;
use EssentialsBE\Commands\BreakCommand;
use EssentialsBE\Commands\BigTreeCommand;
use EssentialsBE\Commands\Broadcast;
use EssentialsBE\Commands\Burn;
use EssentialsBE\Commands\ClearInventory;
use EssentialsBE\Commands\Compass;
use EssentialsBE\Commands\Condense;
use EssentialsBE\Commands\Depth;
use EssentialsBE\Commands\Economy\Balance;
use EssentialsBE\Commands\Economy\Eco;
use EssentialsBE\Commands\Economy\Pay;
use EssentialsBE\Commands\Economy\Sell;
use EssentialsBE\Commands\Economy\SetWorth;
use EssentialsBE\Commands\Economy\Worth;
use EssentialsBE\Commands\Economy\BalanceTop;
use EssentialsBE\Commands\EssentialsBE;
use EssentialsBE\Commands\Feed;
use EssentialsBE\Commands\Extinguish;
use EssentialsBE\Commands\Fly;
use EssentialsBE\Commands\GetPos;
use EssentialsBE\Commands\God;
use EssentialsBE\Commands\Hat;
use EssentialsBE\Commands\Heal;
use EssentialsBE\Commands\Home\DelHome;
use EssentialsBE\Commands\Home\Home;
use EssentialsBE\Commands\Home\SetHome;
use EssentialsBE\Commands\ItemCommand;
use EssentialsBE\Commands\ItemDB;
use EssentialsBE\Commands\Jump;
use EssentialsBE\Commands\KickAll;
//use EssentialsBE\Commands\Kit;
use EssentialsBE\Commands\Lightning;
use EssentialsBE\Commands\More;
use EssentialsBE\Commands\Mute;
use EssentialsBE\Commands\Near;
use EssentialsBE\Commands\Nick;
use EssentialsBE\Commands\Nuke;
use EssentialsBE\Commands\Override\Gamemode;
use EssentialsBE\Commands\Override\Kill;
use EssentialsBE\Commands\Override\Msg;
use EssentialsBE\Commands\Ping;
use EssentialsBE\Commands\PowerTool\PowerTool;
use EssentialsBE\Commands\PowerTool\PowerToolToggle;
use EssentialsBE\Commands\PTime;
use EssentialsBE\Commands\PvP;
use EssentialsBE\Commands\RealName;
use EssentialsBE\Commands\Repair;
use EssentialsBE\Commands\Reply;
use EssentialsBE\Commands\Seen;
use EssentialsBE\Commands\SetSpawn;
use EssentialsBE\Commands\Spawn;
use EssentialsBE\Commands\Speed;
use EssentialsBE\Commands\Sudo;
use EssentialsBE\Commands\Suicide;
use EssentialsBE\Commands\Teleport\TPA;
use EssentialsBE\Commands\Teleport\TPAccept;
use EssentialsBE\Commands\Teleport\TPAHere;
use EssentialsBE\Commands\Teleport\TPAll;
use EssentialsBE\Commands\Teleport\TPDeny;
use EssentialsBE\Commands\Teleport\TPHere;
use EssentialsBE\Commands\TempBan;
use EssentialsBE\Commands\Top;
use EssentialsBE\Commands\TreeCommand;
use EssentialsBE\Commands\Unlimited;
use EssentialsBE\Commands\Vanish;
use EssentialsBE\Commands\Warp\DelWarp;
use EssentialsBE\Commands\Warp\Setwarp;
use EssentialsBE\Commands\Warp\Warp;
use EssentialsBE\Commands\Whois;
use EssentialsBE\Commands\World;
use EssentialsBE\EventHandlers\OtherEvents;
use EssentialsBE\EventHandlers\PlayerEvents;
use EssentialsBE\EventHandlers\SignEvents;
use EssentialsBE\Events\CreateAPIEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase{
    /** @var BaseAPI */
    private $api;

    public function onEnable(): void{
        if($this->getConfig()->get("enable") === false) {
           $this->setEnabled(false);
        }
        // Before anything else...
        $this->checkConfig();

        // Custom API Setup :3
        $this->getServer()->getPluginManager()->callEvent($ev = new CreateAPIEvent($this, BaseAPI::class));
        $class = $ev->getClass();
        $this->api = new $class($this);

        // Other startup code...
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        
		$this->getLogger()->info(TextFormat::YELLOW . "ยง6Loading...");
        $this->registerEvents();
        $this->registerCommands();
        if(count($p = $this->getServer()->getOnlinePlayers()) > 0){
            $this->getAPI()->createSession($p);
        }
        if($this->getAPI()->isUpdaterEnabled()){
            $this->getAPI()->fetchEssentialsBEUpdate(false);
        }
        $this->getAPI()->scheduleAutoAFKSetter();
    }

    public function onDisable(): void{
        if(count($l = $this->getServer()->getOnlinePlayers()) > 0){
            $this->getAPI()->removeSession($l);
        }
        $this->getAPI()->close();
    }

    /**
     * Function to register all the Event Handlers that EssentialsBE provide
     */
    public function registerEvents(): void{
        $this->getServer()->getPluginManager()->registerEvents(new OtherEvents($this->getAPI()), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvents($this->getAPI()), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SignEvents($this->getAPI()), $this);
    }

    /**
     * Function to register all EssentialsBE's commands...
     * And to override some default ones
     */
    private function registerCommands(): void{
        $commands = [
            new AFK($this->getAPI()),
            new Antioch($this->getAPI()),
            new BigTreeCommand($this->getAPI()),
            new BreakCommand($this->getAPI()),
            new Broadcast($this->getAPI()),
            new Burn($this->getAPI()),
            new ClearInventory($this->getAPI()),
            new Compass($this->getAPI()),
            new Condense($this->getAPI()),
            new Depth($this->getAPI()),
            new EssentialsBE($this->getAPI()),
            new Extinguish($this->getAPI()),
            new Fly($this->getAPI()),
            new GetPos($this->getAPI()),
            new God($this->getAPI()),
            new Hat($this->getAPI()),
            new Heal($this->getAPI()),
            new ItemCommand($this->getAPI()),
            new ItemDB($this->getAPI()),
            new Jump($this->getAPI()),
            new KickAll($this->getAPI()),
            //new Kit($this->getAPI()),
            new Lightning($this->getAPI()),
            new More($this->getAPI()),
            new Mute($this->getAPI()),
            new Near($this->getAPI()),
            new Nick($this->getAPI()),
            new Nuke($this->getAPI()),
            new Ping($this->getAPI()),
            new Feed($this->getAPI()),
            new PTime($this->getAPI()),
            new PvP($this->getAPI()),
            new RealName($this->getAPI()),
            new Repair($this->getAPI()),
            new Seen($this->getAPI()),
            new SetSpawn($this->getAPI()),
            new Spawn($this->getAPI()),
            new Speed($this->getAPI()),
            new Sudo($this->getAPI()),
            new Suicide($this->getAPI()),
            new TempBan($this->getAPI()),
            new Top($this->getAPI()),
            new TreeCommand($this->getAPI()),
            new Unlimited($this->getAPI()),
            new Vanish($this->getAPI()),
            new Whois($this->getAPI()),
            new World($this->getAPI()),
		
            // Messages
            new Msg($this->getAPI()),
            new Reply($this->getAPI()),
		
            // Override
            new Gamemode($this->getAPI()),
            new Kill($this->getAPI())		
		];
	    
		$economyCommands = [
	        new Balance($this->getAPI()),
	        new Eco($this->getAPI()),
	        new Pay($this->getAPI()),
	        new Sell($this->getAPI()),
	        new SetWorth($this->getAPI()),
	        new Worth($this->getAPI()),
	        new BalanceTop($this->getAPI())
		];

		$homeCommands = [
	        new DelHome($this->getAPI()),
	        new Home($this->getAPI()),
	        new SetHome($this->getAPI())
		];

		$powertoolCommands = [
	        new PowerTool($this->getAPI()),
			new PowerToolToggle($this->getAPI())
		];

		$teleportCommands = [
	        new TPA($this->getAPI()),
	        new TPAccept($this->getAPI()),
	        new TPAHere($this->getAPI()),
	        new TPAll($this->getAPI()),
	        new TPDeny($this->getAPI()),
	        new TPHere($this->getAPI())
		];

		$warpCommands = [
	        new DelWarp($this->getAPI()),
	        new Setwarp($this->getAPI()),
	        new Warp($this->getAPI())
		];


		if($this->getServer()->getPluginManager()->getPlugin("SimpleWarp") === null) {
	            foreach($warpCommands as $warpCommand) {
		        if($this->getConfig()->get("warps") === true) {
			    $commands[] = $warpCommand;
		        }
		    }
	    } else {
	        $this->getLogger()->info(TextFormat::YELLOW . "ยง6SimpleWarp installed, disabling EssentialsBE warps...");
	    }

		foreach($teleportCommands as $teleportCommand) {
		    if($this->getConfig()->get("teleporting") === true) {
			 $commands[] = $teleportCommand;
		    }
		}

		foreach($powertoolCommands as $powertoolCommand) {
		    if($this->getConfig()->get("powertool") === true) {
			 $commands[] = $powertoolCommand;
		    }
		}

		foreach($homeCommands as $homeCommand) {
		    if($this->getConfig()->get("homes") === true) {
			 $commands[] = $homeCommand;
		    }
		}
		foreach($economyCommands as $economyCommand) {
		    if($this->getConfig()->get("economy") === true) {
			 $commands[] = $economyCommand;
		    }
		}
	    
        $aliased = [];
        foreach($commands as $cmd){
            /** @var BaseCommand $cmd */
            $commands[$cmd->getName()] = $cmd;
            $aliased[$cmd->getName()] = $cmd->getName();
            foreach($cmd->getAliases() as $alias){
                $aliased[$alias] = $cmd->getName();
            }
        }
        $cfg = $this->getConfig()->get("commands", []);
        foreach($cfg as $del){
            if(isset($aliased[$del])){
                unset($commands[$aliased[$del]]);
            }else{
                $this->getLogger()->debug("\"$del\" command not found inside EssentialsBE, skipping...");
            }
        }
        $this->getServer()->getCommandMap()->registerAll("EssentialsBE", $commands);
    }

    public function checkConfig(): void{
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        if(!file_exists($this->getDataFolder() . "config.yml")){
            $this->saveDefaultConfig();
        }
        $this->saveResource("Economy.yml");
        //$this->saveResource("Kits.yml");
        $this->saveResource("Warps.yml");
        $cfg = $this->getConfig();

        if(!$cfg->exists("version") || $cfg->get("version") !== "0.0.3"){
            $this->getLogger()->debug(TextFormat::RED . "An invalid config file was found, generating a new one...");
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config.yml.old");
            $this->saveDefaultConfig();
            $cfg = $this->getConfig();
        }

        $booleans = ["enable-custom-colors"];
        foreach($booleans as $key){
            $value = null;
            if(!$cfg->exists($key) || !is_bool($cfg->get($key))){
                switch($key){
                    // Properties to auto set true
                    case "safe-afk":
                        $value = true;
                        break;
                    // Properties to auto set false
                    case "enable-custom-colors":
                        $value = false;
                        break;
                }
            }
            if($value !== null){
                $cfg->set($key, $value);
            }
        }

        $integers = ["oversized-stacks", "near-radius-limit", "near-default-radius"];
        foreach($integers as $key){
            $value = null;
            if(!is_numeric($cfg->get($key))){
                switch($key){
                    case "auto-afk-kick":
                        $value = 300;
                        break;
                    case "oversized-stacks":
                        $value = 64;
                        break;
                    case "near-radius-limit":
                        $value = 200;
                        break;
                    case "near-default-radius":
                        $value = 100;
                        break;
                }
            }
            if($value !== null){
                $cfg->set($key, $value);
            }
        }

        $afk = ["safe", "auto-set", "auto-broadcast", "auto-kick", "broadcast"];
        foreach($afk as $key){
            $value = null;
            $k = $this->getConfig()->getNested("afk." . $key);
            switch($key){
                case "safe":
                case "auto-broadcast":
                case "broadcast":
                    if(!is_bool($k)){
                        $value = true;
                    }
                    break;
                case "auto-set":
                case "auto-kick":
                    if(!is_int($k)){
                        $value = 300;
                    }
                    break;
            }
            if($value !== null){
                $this->getConfig()->setNested("afk." . $key, $value);
            }
        }

        $updater = ["enabled", "time-interval", "warn-console", "warn-players", "channel"];
        foreach($updater as $key){
            $value = null;
            $k = $this->getConfig()->getNested("updater." . $key);
            switch($key){
                case "time-interval":
                    if(!is_int($k)){
                        $value = 1800;
                    }
                    break;
                case "enabled":
                case "warn-console":
                case "warn-players":
                    if(!is_bool($k)){
                        $value = true;
                    }
                    break;
                case "channel":
                    if(!is_string($k) || ($k !== "stable" && $k !== "beta" && $k !== "development")){
                        $value = "stable";
                    }
            }
            if($value !== null){
                $this->getConfig()->setNested("updater." . $key, $value);
            }
        }
    }

    /**
     * @return BaseAPI
     */
    public function getAPI(): BaseAPI{
        return $this->api;
    }
}
