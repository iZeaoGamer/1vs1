<?php

namespace OneVsOne\Arena;

use OneVsOne\Main;
use OneVsOne\Util\ConfigManager;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerInteractEvent;
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

    /**
     * @param PlayerInteractEvent $event
     */
    public function onTouch(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        if(serialize($this->plugin->signpos) == serialize($event->getBlock()->asPosition())) {
            $this->plugin->teleportToArena($player);
        }
    }


    public function onQuit(PlayerQuitEvent $event) {

    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event) {
        if(strpos($event->getMessage(), "/") === 0) {
            if($this->plugin->phase != 0) {
                if($event->getMessage() != "/1vs1 leave") {
                    $event->setCancelled();
                    $event->getPlayer()->sendMessage("§cUse /1vs1 leave to leave match");
                }
            }
        }
    }
}