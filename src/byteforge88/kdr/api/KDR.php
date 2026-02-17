<?php

declare(strict_types=1);

namespace byteforge88\kdr\api;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;

use byteforge88\kdr\database\Database;

class KDR {
    use SingletonTrait;
    
    public function isNew(Player|string $player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT * FROM kdr WHERE player = :player");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? true : false;
        } finally {
            $stmt->close();
        }
    }
    
    public function insertIntoDatabase(Player|string $player) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("INSERT INTO kdr (player, kills, deaths) VALUES (:player, :kills, :deaths);");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":kills", 0, SQLITE3_INTEGER);
            $stmt->bindValue(":deaths", 0, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function addKill(Player|string $player) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE kdr SET kills = kills + 1 WHERE player = player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function getKills(Player|string $player) : ?int{
        $player  = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT kills FROM kdr WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? null : (int) $data["kills"];
        } finally {
            $stmt->close();
        }
    }
    
    public function addDeath(Player|string $player) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE kdr SET deaths = deaths + 1 WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function getDeaths(Player|string $player) : ?int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT deaths FROM kdr WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? null : (int) $data["deaths"];
        }
    }
    
    public function getKDR(Player|string $player) : float{
        $kills = $this->getKills($player);
        $deaths = $this->getDeaths($player);
        
        if ($deaths === 0) {
            return 0.0;
        }
        
        return $kills / $deaths;
    }
}