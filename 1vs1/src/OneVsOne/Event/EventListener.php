<?php

namespace OneVsOne\Event;

use OneVsOne\Arena\Arena;
use OneVsOne\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\Position;
use pocketmine\tile\Sign;

/**
 * Class EventListener
 * @package OneVsOne\Event
 */
class EventListener implements Listener {

    /** @var  Main $plugin */
    public $plugin;

    /**
     * EventListener constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param Position $signPos
     * @return Arena
     */
    function getArenaBySign(Position $signPos):Arena {
        foreach($this->plugin->arenas as $arena) {
            if($arena instanceof Arena) {
                if($arena->signpos->getX() == $signPos->getX() && $arena->signpos->getY() == $signPos->getY() && $arena->signpos->getZ() == $signPos->getZ() && $signPos->getLevel()->getName() == $arena->signpos->getLevel()) {
                    return $arena;
                }
            }
        }
    }
}