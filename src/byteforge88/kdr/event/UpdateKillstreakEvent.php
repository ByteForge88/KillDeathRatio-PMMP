<?php

declare(strict_types=1);

namespace byteforge88\kdr\event;

use pocketmine\event\Event;

class UpdateKillstreakEvent extends Event {
    
    private string $name;
    
    public function __construct(string $name) {
        $this->name = $name;
    }
    
    public function getName() : string{
        return $this->name;
    }
}