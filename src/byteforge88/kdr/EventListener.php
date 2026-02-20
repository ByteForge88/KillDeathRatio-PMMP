<?php

declare(strict_types=1);

namespace byteforge88\kdr;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\ChunkUnloadEvent;
use pocketmine\event\world\WorldUnloadEvent;

use pocketmine\Server;

use byteforge88\kdr\api\KDR;
use byteforge88\kdr\event\UpdateKillEvent;
use byteforge88\kdr\event\UpdateKillstreakEvent;
use byteforge88\kdr\event\UpdateDeathEvent;
use byteforge88\kdr\floatingtext\FloatingText;
use byteforge88\kdr\floatingtext\KillFloatingText;
use byteforge88\kdr\floatingtext\KillstreakFloatingText;
use byteforge88\kdr\floatingtext\DeathFloatingText;

class EventListener implements Listener {
    
    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $api = KDR::getInstance();
        
        if ($api->isNew($player)) {
            $api->insertIntoDatabase($player);
        }
    }
    
    public function onJoin(PlayerJoinEvent $event) : void{
        KillFloatingText::updateKillFloatingText();
        DeathFloatingText::updateDeathFloatingText();
        KillstreakFloatingText::updateKillstreakFloatingText();
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
    
    public function onChunkLoad(ChunkLoadEvent $event) : void{
        FloatingText::loadFromFile();
    }

    public function onChunkUnload(ChunkUnloadEvent $event) : void{
        FloatingText::saveFile();
    }

    public function onWorldUnload(WorldUnloadEvent $event) : void{
        FloatingText::saveFile();
    }

    /**
     * Fix this check FloatingText.php
     * Make it invisible to the player thats teleporting...
     * Right now it hides it from all when a player teleports
     */
    public function onTeleport(EntityTeleportEvent $event) : void{
        $entity = $event->getEntity();
        
        if ($entity instanceof Player) {
            $fromWorld = $event->getFrom()->getWorld();
            $toWorld = $event->getTo()->getWorld();
            
            if ($fromWorld !== $toWorld) {
                foreach (FloatingText::$floatingText as $tag => [$position, $floatingText]) {
                    if ($position->getWorld() === $fromWorld) {
                        FloatingText::makeInvisible($tag);
                    }
                }
            }
        }
    }
    
    public function onUpdateKill(UpdateKillEvent $event) : void{
        KillFloatingText::updateKillFloatingText();
    }
    
    public function onUpdateDeath(UpdateDeathEvent $event) : void{
        DeathFloatingText::updateDeathFloatingText();
    }
    
    public function onUpdateKillstreak(UpdateKillstreakEvent $event) : void{
        KillstreakFloatingText::updateKillstreakFloatingText();
    }
}