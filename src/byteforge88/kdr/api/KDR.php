<?php

declare(strict_types=1);

namespace byteforge88\kdr\api;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;

use byteforge88\kdr\database\Database;

use byteforge88\kdr\event\UpdateKillEvent;
use byteforge88\kdr\event\UpdateKillstreakEvent;
use byteforge88\kdr\event\UpdateDeathEvent;

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
        $stmt = Database::getInstance()->getSQL()->prepare("INSERT INTO kdr (player, kills, killstreak, deaths) VALUES (:player, :kills, :killstreak, :deaths);");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":kills", 0, SQLITE3_INTEGER);
            $stmt->bindValue(":killstreak", 0, SQLITE3_INTEGER);
            $stmt->bindValue(":deaths", 0, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function addKill(Player|string $player) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $e = new UpdateKillEvent($player);
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE kdr SET kills = kills + 1 WHERE player = player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
            $e->call();
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
    
    public function addKillstreak(Player|string $player) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $e = new UpdateKillstreakEvent($player);
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE kdr SET killstreak = killstreak + 1 WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
            $e->call();
        }
    }
    
    public function getKillstreak(Player|string $player) : ?int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT killstreak FROM kdr WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? null : (int) $data["killstreak"];
        } finally {
            $stmt->close();
        }
    }
    
    public function addDeath(Player|string $player) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $e = new UpdateDeathEvent($player);
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE kdr SET deaths = deaths + 1 WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
            $e->call();
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
        } finally {
            $stmt->close();
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
    
    public function getTopKills(int $limit) : array{
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT player, kills FROM kdr ORDER BY kills DESC LIMIT :limit");
        
        try {
            $stmt->bindValue(":limit", $limit, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            $data = [];
            
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $data[] = [
                    "player" => $row["player"],
                    "kills" => (int) $row["kills"]
                ];
            }
            
            $result->finalize();
            
            return $data;
        } finally {
            $stmt->close();
        }
    }
    
    public function getTopKillstreak(int $limit) : array{
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT player, killstreak FROM kdr ORDER BY killstreak DESC LIMIT :limit");
        
        try {
            $stmt->bindValue(":limit", $limit, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            $data = [];
            
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $data[] = [
                    "player" => $row["player"],
                    "killstreak" => (int) $row["killstreak"]
                ];
            }
            
            $result->finalize();
            
            return $data;
        } finally {
            $stmt->close();
        }
    }
    
    public function getTopDeaths(int $limit) : array{
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT player, deaths FROM kdr ORDER BY deaths DESC LIMIT :limit");
        
        try {
            $stmt->bindValue(":limit", $limit, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            $data = [];
            
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $data[] = [
                    "player" => $row["player"],
                    "deaths" => (int) $row["deaths"]
                ];
            }
            
            $result->finalize();
            
            return $data;
        } finally {
            $stmt->close();
        }
    }
}