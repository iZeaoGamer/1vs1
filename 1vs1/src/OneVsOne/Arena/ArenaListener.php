<?php

namespace OneVsOne\Arena;

use OneVsOne\Main;
use OneVsOne\Util\ConfigManager;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\tile\Sign;

/**
 * Class ArenaListener
 * @package OneVsOne\Arena
 * @author GamakCZ
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
        if($this->arena->inGame($player)) {

        }
    }

    /**
     * @param SignChangeEvent $event
     */
    public function onSignCreate(SignChangeEvent $event) {
        $player = $event->getPlayer();
        $tile = $player->getLevel()->getTile($event->getBlock());
        if($player->hasPermission("1vs1.sign")) {
            if($tile instanceof Sign) {
                if($tile->getText()[0] == "[1vs1]") {
                    $lines = ConfigManager::getConfig()->get("sign-format");
                    $tile->setText($lines["line1"], $lines["line2"], $lines["line3"], $lines["line4"]);
                }
            }
        }
    }
}