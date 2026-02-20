<?php

declare(strict_types=1);

namespace byteforge88\kdr\utils;

use pocketmine\player\Player;

use pocketmine\Server;

use byteforge88\kdr\api\KDR;

class Utils {
    
    public static function checkKillstreak(Player $player) : void{
        $name = $player->getName();
        $server = Server::getInstance();
        $killstreak = KDR::getInstance()->getKillstreak($player);
        
        if ($killstreak === 5) {
            $server->broadcastMessage($name . " is on a 5 killstreak!");
            return;
        }
        
        if ($killstreak === 10) {
            $server->broadcastMessage($name . " is on a 10 killstreak!");
            return;
        }
        
        if ($killstreak === 15) {
            $server->broadcastMessage($name . " is on a 15 killstreak");
            return;
        }
        
        if ($killstreak === 20) {
            $server->broadcastMessage($name . " is on a 20 killstreak!");
            return;
        }
        
        if ($killstreak > 20) {
            $server->broadcastMessage($name . " is on a " . $killstreak . " killstreak!");
            return;
        }
    }
}