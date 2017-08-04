<?php

namespace OneVsOne\Arena;

use OneVsOne\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

/**
 * Class ArenaListener
 * @package OneVsOne\Arena
 */
class ArenaListener implements Listener {

    /** @var  Arena $arena */
    public $arena;

    /** @var  Main $plugin */
    public $plugin;

    /**
     * ArenaListener constructor.
     * @param Main $plugin
     * @param Arena $arena
     */
    public function __construct(Main $plugin, Arena $arena) {
        $this->arena = $arena;
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerMoveEvent $event
     */
    public function onMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
    }
}