<?php

declare(strict_types=1);

namespace byteforge88\kdr\floatingtext\leaderboard;

use byteforge88\kdr\api\KDR;

use byteforge88\kdr\floatingtext\FloatingText;

class KillFloatingText {
    
    public static function updateKillFloatingText() : void{
        $top_kills = KDR::getInstance()->getTopKills();
        $i = 1;
        
        $text = "-= Top 10 Killer's =-\n";
        
        foreach ($top_kills as $data) {
            $text .= $i . ". " . $data["player"] . " - " . number_format($kills) . " kills\n";
            $i++;
        }
        
        FloatingText::update("kill_leaderboard", $text);
    }
}