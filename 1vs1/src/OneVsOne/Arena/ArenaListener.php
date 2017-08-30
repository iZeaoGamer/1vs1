<?php

namespace OneVsOne\Arena;

use OneVsOne\Main;
use OneVsOne\Util\ConfigManager;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\tile\Sign;

/**
 * Class ArenaListener
 * @package OneVsOne\Arena
 * @author GamakCZ
 */
class ArenaListener implements Listener {

    /** @var  Arena $plugin */
    public $plugin;

    /**
     * ArenaListener constructor.
     * @param Arena $plugin
     */
    public function __construct(Arena $plugin) {
        $this->plugin = $plugin;
    }

    public function onQuit(PlayerQuitEvent $event) {

    }

    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event) {
    }
}