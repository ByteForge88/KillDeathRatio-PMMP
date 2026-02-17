<?php

declare(strict_types=1);

namespace byteforge88\kdr\floatingtext\leaderboard;

use byteforge88\kdr\api\KDR;

use byteforge88\kdr\floatingtext\FloatingText;

class DeathFloatingText {
    
    public static function updateDeathFloatingText() : void{
        $top_deaths = KDR::getInstance()->getTopDeaths();
        $i = 1;
        
        $text = "-= Top 10 Death's =-\n";
        
        foreach ($top_deaths as $data) {
            $text .= $i . ". " . $data["player"] . " - " . number_format($data["deaths"]) . " deaths\n";
            $i++;
        }
        
        FloatingText::update("death_leaderboard", $text);
    }
}