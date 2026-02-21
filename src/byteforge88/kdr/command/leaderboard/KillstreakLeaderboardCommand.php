<?php

declare(strict_types=1);

namespace byteforge88\kdr\command\leaderboard;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;

use CortexPE\Commando\BaseCommand;

class KillstreakLeaderboardCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $top_killstreak = KDR::getInstance()->getTopKillstreak();
        $i = 1;
        
        $sender->sendMessage("-= Top 10 Killstreak =-");
        
        foreach ($top_killstreak as $data) {
            $sender->sendMessage($i . ". " . $data["player"] . " - " . number_format($data["killstreak"]) . " killstreak\n");
            $i++;
        }
        
        
    }
    
    public function getPermission() : string{
        return "mineconomy.killstreakleaderboard";
    }
}