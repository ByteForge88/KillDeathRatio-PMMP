<?php

declare(strict_types=1);

namespace byteforge88\kdr\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\kdr\api\KDR;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\RawStringArgument;

class SeeKDRCommand extends BaseCommand {
    
    protected function prepare() : void{
        $this->setPermission($this->getPermission());
        $this->registerArgument(0, new RawStringArgument("player"));
    }
    
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }
        
        $api = KDR::getInstance();
        
        if ($api->isNew($args["player"])) {
            $sender->sendMessage("Player not found!");
            return;
        }
        
        $kills = $api->getKills($args["player"]);
        $deaths = $api->getDeaths($args["player"]);
        $kdr = $api->getKDR($args["player"]);
        
        $sender->sendMessage("-= " . $args["player"] . " KDR stats =-");
        $sender->sendMessage("kills: " . number_format($kills));
        $sender->sendMessage("deaths: " . number_format($deaths));
        $sender->sendMessage("KDR: " . $kdr);
    }
    
    public function getPermission() : string{
        return "killdeathratio.seekdr";
    }
}