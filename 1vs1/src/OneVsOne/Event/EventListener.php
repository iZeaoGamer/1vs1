<?php

namespace OneVsOne\Event;

use OneVsOne\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Sign;

/**
 * Class EventListener
 * @package OneVsOne\Event
 */
class EventListener implements Listener {

    /** @var  Main $plugin */
    public $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onTouch(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $tile = $event->getPlayer()->getLevel()->getTile($event->getBlock());
        if($tile instanceof Sign) {
            if($tile->getText()[0] == $this->plugin->configManager->getConfigData("SignLine-1")) {

            }
        }
    }
}