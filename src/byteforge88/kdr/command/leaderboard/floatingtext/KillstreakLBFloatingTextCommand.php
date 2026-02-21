<?php

declare(strict_types=1);

namespace byteforge88\kdr\command\leaderboard\floatingtext;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;
use byteforge88\kdr\floatingtext\FloatingText;

use CortexPE\Commando\BaseCommand;

class KillstreakLBFloatingTextCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $position = $sender->getPosition();
        $top_killstreak = KDR::getInstance()->getTopKillstreak();
        $i = 1;
        
        $text = "-= Top 10 Killstreak =-\n";
        
        foreach ($top_killstreak as $data) {
            $text .= $i . ". " . $data["player"] . " - " . number_format($data["killstreak"]) . " killstreak\n";
            $i++;
        }
        
        FloatingText::create($position, "killstreak_leaderboard", $text);
        $sender->sendMessage("You have successfully spawned in the floating text!");
    }
    
    public function getPermission() : string{
        return "killdeathratio.killstreakfloatingtext";
    }
}