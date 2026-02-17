<?php

declare(strict_types=1);

namespace byteforge88\kdr;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use byteforge88\kdr\api\KDR;

class EventListener implements Listener {
    
    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $api = KDR::getInstance();
        
        if ($api->isNew($player)) {
            $api->insertIntoDatabase($player);
        }
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
}