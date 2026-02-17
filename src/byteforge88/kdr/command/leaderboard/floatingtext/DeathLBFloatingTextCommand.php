<?php

declare(strict_types=1);

namespace byteforge88\kdr\command\leaderboard\floatingtext;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;
use byteforge88\kdr\floatingtext\FloatingText;

use CortexPE\Commando\BaseCommand;

class DeathLBFloatingTextCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $position = $sender->getPosition();
        $top_deaths = KDR::getInstance()->getTopDeaths();
        $i = 1;
        
        $text = "-= Top 10 Death's =-\n";
        
        foreach ($top_deaths as $data) {
            $text .= $i . ". " . $data["player"] . " - " . number_format($data["deaths"]) . " deaths\n";
            $i++;
        }
        
        FloatingText::create($position, "death_leaderboard", $text);
        $sender->sendMessage("You have successfully spawned in the floating text!");
    }
    
    public function getPermission() : string{
        return "killdeathratio.deathfloatingtext";
    }
}