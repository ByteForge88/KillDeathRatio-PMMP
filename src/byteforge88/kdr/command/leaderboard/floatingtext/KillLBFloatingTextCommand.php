<?php

declare(strict_types=1);

namespace byteforge88\kdr\command\leaderboard\floatingtext;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;
use byteforge88\kdr\floatingtext\FloatingText;

use CortexPE\Commando\BaseCommand;

class KillLBFloatingTextCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $position = $sender->getPosition();
        $top_kills = KDR::getInstance()->getTopKills();
        $i = 1;
        
        $text = "-= Top 10 Killer's =-\n";
        
        foreach ($top_kills as $data) {
            $text .= $i . ". " . $data["player"] . " - " . number_format($kills) . " kills\n";
            $i++;
        }
        
        FloatingText::create($position, "kill_leaderboard", $text);
        $sender->sendMessage("You have successfully spawned in the floating text!");
    }
    
    public function getPermission() : string{
        return "killdeathratio.killfloatingtext";
    }
}