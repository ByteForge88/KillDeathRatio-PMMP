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
use byteforge88\kdr\event\UpdateDeathEvent;
use byteforge88\kdr\floatingtext\FloatingText;
use byteforge88\kdr\floatingtext\KillFloatingText;
use byteforge88\kdr\floatingtext\DeathFloatingText;

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
        KillFloatingText::updateKillFloatingText();
        DeathFloatingText::updateDeathFloatingText();
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
        $name = $event->getName();
        $player = Server::getInstance()->getPlayerExact($name);
        
        if ($player !== null) {
            Scoreboard::updateTags($player);
        }
        
        KillFloatingText::updateKillFloatingText();
    }
    
    public function onUpdateDeath(UpdateDeathEvent $event) : void{
        $name = $event->getName();
        $player = Server::getInstance()->getPlayerExact($name);
        
        if ($player !== null) {
            Scoreboard::updateTags($player);
        }
        
        DeathFloatingText::updateDeathFloatingText();
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