<?php

namespace OneVsOne\Arena;

use pocketmine\scheduler\Task;

class ArenaScheduler extends Task {

    /** @var  Arena $plugin */
    public $plugin;

    public function __construct(Arena $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick) {

    }
}