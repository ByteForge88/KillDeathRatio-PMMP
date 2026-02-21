<?php

declare(strict_types=1);

namespace byteforge88\kdr\command\leaderboard;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\KDR;

use CortexPE\Commando\BaseCommand;

class DeathsLeaderboardCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $top_deaths = KDR::getInstance()->getTopDeaths();
        $i = 1;
        
        $sender->sendMessage("-= Top 10 Death's =-");
        
        foreach ($top_deaths as $data) {
            $sender->sendMessage($i . ". " . $data["player"] . " - " . number_format($data["deaths"]) . " deaths\n");
        }
    }
    
    public function getPermission() : string{
        return "killdeathratio.deathleaderboard";
    }
}