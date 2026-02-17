<?php

declare(strict_types=1);

namespace byteforge88\kdr\scoreboard;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;

use Ifera\ScoreHud\ScoreHud;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use Ifera\ScoreHud\event\PlayerTagsUpdateEvent;

class Scoreboard {
    
    public static function updateTags(Player $player) : void{
        if (class_exists(ScoreHud::class)) {
            $api = KDR::getInstance();
            $kills = $api->getKills($player);
            $deaths = $api->getDeaths($player);
            $kdr = $api->getKDR($player);
            
            $e = PlayerTagsUpdateEvent(
                $player,
                [
                    new ScoreTag("killdeathratio.kills", number_format($kills)),
                    new ScoreTag("killdeathratio.deaths", number_format($deaths)),
                    new ScoreTag("killdeathratio.kdr", (string) $kdr)
                ]
            );
            
            $e->call();
        }
    }
}