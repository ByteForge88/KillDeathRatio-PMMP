<?php

declare(strict_types=1);

namespace byteforge88\kdr\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;

use CortexPE\Commando\BaseCommand;

class KDRCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $api = KDR::getInstance();
        $kills = $api->getKills($sender);
        $deaths = $api->getDeaths($sender);
        $kdr = $api->getKDR($Sender);
        
        $sender->sendMessage("-= Your KDR stats =-");
        $sender->sendMessage("kills: " . number_format($kills));
        $sender->sendMessage("deaths: " . number_format($deaths));
        $sender->sendMessage("KDR: " . $kdr);
    }
    
    public function getPermission() : string{
        return "killdeathratio.kdr";
    }
}