<?php

declare(strict_types=1);

namespace byteforge88\kdr\command\leaderboard;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;

use CortexPE\Commando\BaseCommand;

class KillLeaderboard extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $top_kills = KDR::getInstance()->getTopKills();
        $i = 1;
        
        $sender->sendMessage("-= Top 10 Killer's =-");
        
        foreach ($top_kills as $data) {
            $sender->sendMessage($i . ". " . $data["player"] . " - " . number_format($data["kills"]) . " kills\n");
            $i++;
        }
    }
    
    public function getPermission() : string{
        return "killdeathratio.killleaderboard";
    }
}