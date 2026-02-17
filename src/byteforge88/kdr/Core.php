<?php

declare(strict_types=1);

namespace byteforge88\kdr;

use pocketmine\plugin\PluginBase;

use byteforge88\kdr\database\Database;

use byteforge88\kdr\command\KDRCommand;
use byteforge88\kdr\command\SeeKDRCommand;
use byteforge88\kdr\command\leaderboard\KillsLeaderboard;
use byteforge88\kdr\command\leaderboard\DeathsLeaderboard;

use CortexPE\Commando\PacketHooker;

class Core extends PluginBase {
    
    protected static self $instance;
    
    protected function onLoad() : void{
        self::$instance = $this;
    }
    
    protected function onEnable() : void{
        $server = $this->getServer();
        
        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }
        
        $server->getPluginManager()->registerEvents(new EventListener(), $this);
        $server->getCommandMap()->registerAll("KillDeathRatio", [
            new KDRCommand($this, "kdr", "View your KDR stats"),
            new SeeKDRCommand($this, "seekdr", "View someone's KDR stats"),
            new KillsLeaderboard($this, "topkills", "View the top 10 killer's"),
            new DeathsLeaderboard($this, "topdeaths", "View the top 10 death's"),
        ]);
    }
    
    public function onDisable() : void{
        Database::getInstance()->close();
    }
    
    public static function getInstance() : self{
        return self::$instance;
    }
}