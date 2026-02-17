<?php

declare(strict_types=1);

namespace byteforge88\kdr;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use byteforge88\kdr\api\KDR;

use byteforge88\kdr\scoreboard\Scoreboard;

use Ifera\ScoreHud\event\TagsResolveEvent;

class EventListener implements Listener {
    
    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $api = KDR::getInstance();
        
        if ($api->isNew($player)) {
            $api->insertIntoDatabase($player);
        }
    }
    
    public function onJoin(PlayerJoinEvent $event) : void{
        Scoreboard::updateTags($event->getPlayer());
    }
    
    public function onDeath(PlayerDeathEvent $event) : void{
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();
        $api = KDR::getInstance();
        
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            
            $api->addKill($damager);
        }
        
        $api->addDeath($player);
    }
    
    public function onTagsResolve(TagsResolveEvent $event) : void{
        $player = $event->getPlayer();
        $tag = $event->getTag();
        $api = KDR::getInstance();
        $kills = $api->getKills($player);
        $deaths = $api->getDeaths($player);
        $kdr = $api->getKDR($player);
        
        switch ($tag->getName()) {
            case "killdeathratio.kills":
                $tag->setValue(number_format($kills));
            break;
            
            case "killdeathratio.deaths":
                $tag->setValue(number_format($deaths));
            break;
            
            case "killdeathratio.kdr":
                $tag->setValue((string) $kdr);
            break;
        }
    }
}