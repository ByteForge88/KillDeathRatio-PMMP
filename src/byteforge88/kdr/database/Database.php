<?php

declare(strict_types=1);

namespace byteforge88\kdr;

use SQLite3;

use pocketmine\utils\SingletonTrait;

use byteforge88\kdr\Core;

class Database {
    use SingletonTrait;
    
    protected SQLite3 $sql;
    
    public function __construct() {
        $folder = Core::getInstance()->getDataFolder() . "database/";
        
        @mkdir($folder);
        
        $this->sql = new SQLite3($folder . "database.db");
        
        $this->sql()->exec("CREATE TABLE IF NOT EXISTS kdr (player TEXT PRIMARY KEY, kills INT, deaths INT);");
    }
    
    public function close() : void{
        $this->sql->close();
    }
    
    public function getSQL() : SQLite3{
        return $this->sql;
    }
}