<?php

declare(strict_types=1);

namespace byteforge88\kdr;

use pocketmine\plugin\PluginBase;

use byteforge88\kdr\database\Database;

use byteforge88\kdr\command\KDRCommand;
use byteforge88\kdr\command\SeeKDRCommand;
use byteforge88\kdr\command\leaderboard\KillsLeaderboardCommand;
use byteforge88\kdr\command\leaderboard\KillstreakLeaderboardCommand;
use byteforge88\kdr\command\leaderboard\DeathsLeaderboardCommand;
use byteforge88\kdr\command\leaderboard\floatingtext\KillLBFloatingTextCommand;
use byteforge88\kdr\command\leaderboard\floatingtext\KillstreakLBFloatingTextCommand;
use byteforge88\kdr\command\leaderboard\floatingtext\DeathLBFloatingTextCommand;

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
            new KillsLeaderboardCommand($this, "topkills", "View the top 10 killer's"),
            new KillstreakLeaderboardCommand($this, "topkillstreak", "View the top 10 killstreak's"),
            new DeathsLeaderboardCommand($this, "topdeaths", "View the top 10 death's"),
            new KillLBFloatingTextCommand($this, "killfloatingtext", "Spawn in a FT with the top 10 killers", ["kft"]),
            new KillstreakLBFloatingTextCommand($this, "killstreakfloatingtext", "Spawn in a FT with the top 10 killstreaks", ["ksft"]),
            new DeathLBFloatingTextCommand($this, "deathfloatingtext", "Spawn in a FT with the top 10 deaths", ["dft"])
        ]);
    }
    
    public function onDisable() : void{
        Database::getInstance()->close();
    }
    
    public static function getInstance() : self{
        return self::$instance;
    }
}