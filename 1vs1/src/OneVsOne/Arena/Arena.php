<?php

declare(strict_types=1);

namespace OneVsOne\Arena;

use OneVsOne\ArenaManager;
use onevsone\OneVsOne;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\tile\Sign;

/**
 * Class Arena
 * @package onevsone\arena
 */
class Arena implements Listener {

    const SIGN_PREFIX = "§6§l[1vs1]";
    const SIGN_SLOTS = "§9[ §b%p §3/ §b2 §9]";
    const SIGN_STATUS = ["§aLobby", "§eFull", "§cInGame", "§2Restarting..."];
    const SIGN_MAP = "§8Map: §7%m";

    /** @var Player $player1 */
    public $player1 = null;

    /** @var Player $player2 */
    public $player2 = null;

    /** @var int $phase */
    public $phase = 0;

    /** @var array $config */
    public $config = [];

    /** @var OneVsOne $plugin */
    public $plugin;

    /** @var int $startTime */
    public $startTime = 10;

    /** @var int $gameTime */
    public $gameTime = 600;

    /** @var int $restartTime */
    public $restartTime = 10;

    /**
     * Arena constructor.
     * @param ArenaManager $arenaMgr
     * @param array $config
     */
    public function __construct(ArenaManager $arenaMgr, array $config) {
        $this->plugin = $arenaMgr->plugin;
        $this->config = $config;
        $this->loadGame();
    }

    public function loadGame() {
        $this->startTime = 10;
        $this->gameTime = 600;
        $this->restartTime = 10;
        $this->player1 = null;
        $this->player2 = null;
        $this->phase = 0;
    }

    /**
     * @param Player $player
     */
    public function joinPlayer(Player $player) {
        if($this->player1 === null) {
            $this->player1 = $player;
            $player->teleport($this->plugin->getServer()->getLevelByName($this->config["level"])->getSpawnLocation());
            $player->teleport($this->config["spawn"]["1"][0], $this->config["spawn"]["1"][1], $this->config["spawn"][2]);
        }
        elseif($this->player2 === null) {
            $this->player2 = $player;
            $player->teleport($this->plugin->getServer()->getLevelByName($this->config["level"])->getSpawnLocation());
            $player->teleport($this->config["spawn"]["2"][0], $this->config["spawn"]["1"][1], $this->config["spawn"][2]);
        }
        else {
            $player->sendMessage(OneVsOne::getPrefix()."§cArena is full!");
            return;
        }
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function inGame(Player $player): bool {
        if($player->getName() == $this->player1->getName()) {
            return true;
        }
        elseif($player->getName() == $this->player2->getName()) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @return Player[] $players
     */
    public function getPlayers(): array {
        $players = [];
        if($this->player1 instanceof Player) {
            array_push($players, $this->player1);
        }
        if($this->player2 instanceof Player) {
            array_push($players, $this->player2);
        }
        return $players;
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();

        $tile = $player->getLevel()->getTile($event->getBlock()->asVector3());

        if(!$tile instanceof Sign) {
            $sign = $this->config["sign"];
            $pos = new Position($sign[0], $sign[1], $sign[2], $this->plugin->getServer()->getLevelByName($sign[3]));
            if($pos->equals($event->getBlock()->asVector3())) {
                $this->joinPlayer($player);
            }
        }
    }

    /**
     * @param EntityLevelChangeEvent $event
     */
    public function onLevelChange(EntityLevelChangeEvent $event) {
        $player = $event->getEntity();
        if(!$player instanceof Player) {
            return;
        }
        if(!$this->inGame($player)) {
            return;
        }
        if($this->phase === 0) {
            if($player->getName() == $this->player1->getName()) {
                $this->player1 = null;
            }
            else {
                $this->player2 = null;
            }
            $player->sendMessage(OneVsOne::getPrefix()."§a1vs1 arena successfully leaved!");
        }
        elseif($this->phase === 1) {
            /** @var Player $winner */
            $winner = null;
            if($player->getName() == $this->player1->getName()) {
                $this->player1 = null;
                $winner = $this->player2;
            }
            else {
                $this->player2 = null;
                $winner = $this->player1;
            }
            $player->sendMessage(OneVsOne::getPrefix()."§a1vs1 arena successfully leaved!");
            $winner->addTitle("§aYOU WON!", "§7{$player->getName()} lost.");
            $this->phase = 2;
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        if(!$this->inGame($player)) {
            return;
        }
        if($this->phase === 0) {
            if($player->getName() == $this->player1->getName()) {
                $this->player1 = null;
            }
            else {
                $this->player2 = null;
            }
            $player->sendMessage(OneVsOne::getPrefix()."§a1vs1 arena successfully leaved!");
        }
        elseif($this->phase === 1) {
            /** @var Player $winner */
            $winner = null;
            if($player->getName() == $this->player1->getName()) {
                $this->player1 = null;
                $winner = $this->player2;
            }
            else {
                $this->player2 = null;
                $winner = $this->player1;
            }
            $player->sendMessage(OneVsOne::getPrefix()."§a1vs1 arena successfully leaved!");
            $winner->addTitle("§aYOU WON!", "§7{$player->getName()} lost.");
            $this->phase = 2;
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if(!$entity instanceof Player) {
            return;
        }
        $lastDmg = $entity->getLastDamageCause();
        if(!$lastDmg instanceof EntityDamageByEntityEvent) {
            return;
        }
        $damager = $lastDmg->getDamager();
        if(!$damager instanceof Player) {
            return;
        }
        if(!$this->inGame($entity)) {
            return;
        }
        if(!$this->inGame($damager)) {
            $event->setCancelled(true);
            return;
        }
        if($entity->getHealth()-$lastDmg->getDamage() <= 0) {
            $event->setCancelled(true);
            $entity->setGamemode($entity::SPECTATOR);
            $entity->addTitle("§cYOU LOST!", "§7{$damager->getName()} won.");
            $damager->addTitle("§aYOU WON!", "§7{$entity->getName()} lost.");
            $this->phase = 2;
            return;
        }
    }
}
