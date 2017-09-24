<?php

namespace OneVsOne\Arena;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

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
        $pos1 = $this->getArena()->signpos;
        $pos2 = $event->getBlock()->asPosition();
        if($pos1 != null) {
            if($pos1->equals($pos2->asVector3()) && $pos1->getLevel()->getName() == $pos2->getLevel()->getName()) {
                $this->getArena()->teleportToArena($player);
            }
        }
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        if($this->getArena()->inGame($player)) {
            $this->getArena()->endGame($player);
        }
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event) {
        if(isset($this->getArena()->players[strtolower($event->getPlayer()->getName())])) {
            unset($this->getArena()->players[strtolower($event->getPlayer()->getName())]);
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if($entity instanceof Player) {
            $ingame = false;
            foreach ($this->getArena()->players as $player) {
                if($player->getName() == $entity->getName()) {
                    $ingame = true;
                }
            }
            if($ingame == true) {
                if($event instanceof EntityDamageByEntityEvent) {
                    if($this->getArena()->phase != 2) {
                        $event->setCancelled(true);
                    }
                }
            }
        }
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event) {
        if(strpos($event->getMessage(), "/") === 0) {
            if($this->getArena()->phase != 0) {
                if($event->getMessage() != "/1vs1 leave") {
                    if($this->getArena()->inGame($event->getPlayer())) {
                        $event->getPlayer()->sendMessage("§cUse /1vs1 leave to leave match");
                        $event->setCancelled();
                    }
                }
                else {
                    if($this->getArena()->inGame($event->getPlayer())) {
                        unset($this->getArena()->players[strtolower($event->getPlayer()->getName())]);
                        $event->getPlayer()->setGamemode(Player::SURVIVAL);
                        $event->getPlayer()->getInventory()->clearAll();
                        $event->getPlayer()->removeAllEffects();
                        $event->getPlayer()->teleport($this->getArena()->plugin->getServer()->getDefaultLevel()->getSpawnLocation());
                        $event->setCancelled();
                    }
                }
            }
        }
    }

    public function getArena():Arena {
        return $this->plugin;
    }
}