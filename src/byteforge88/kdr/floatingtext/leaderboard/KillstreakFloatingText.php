<?php

declare(strict_types=1);

namespace byteforge88\kdr\floatingtext\leaderboard;

use byteforge88\kdr\api\KDR;

use byteforge88\kdr\floatingtext\FloatingText;

class KillstreakFloatingText {
    
    public static function updateKillstreakFloatingText() : void{
        $top_killstreak = KDR::getInstance()->getTopKillstreak();
        $i = 1;
        
        $text = "-= Top 10 Killstreak =-\n";
        
        foreach ($top_killstreak as $data) {
            $text .= $i . ". " . $data["player"] . " - " . number_format($data["killstreak"]) . " killstreak\n";
            $i++;
        }
        
        FloatingText::update("killstreak_leaderboard", $text);
    }
}