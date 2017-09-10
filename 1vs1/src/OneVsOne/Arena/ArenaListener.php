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
        $pos1 = $this->plugin->signpos;
        $pos2 = $event->getBlock()->asPosition();
        /*if($pos1->getX() == $pos2->getX() && $pos1->getY() == $pos2->getY() && $pos1->getZ() == $pos2->getZ() && $pos2->getLevel()) {
            $this->plugin->teleportToArena($player);
        }*/
        if($pos1 != null) {
            if($pos1->equals($pos2->asVector3()) && $pos1->getLevel()->getName() == $pos2->getLevel()->getName()) {
                $this->plugin->teleportToArena($player);
            }
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
                    if($event->getPlayer()->getLevel()->getName() == $this->plugin->pos1->getLevel()->getName()) {
                        $event->setCancelled();
                        $event->getPlayer()->sendMessage("§cUse /1vs1 leave to leave match");
                    }
                }
            }
        }
    }
}