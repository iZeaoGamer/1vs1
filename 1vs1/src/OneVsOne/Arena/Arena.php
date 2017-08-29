<?php

namespace OneVsOne\Arena;

use OneVsOne\Main;
use OneVsOne\Util\ConfigManager;
use pocketmine\level\Position;
use pocketmine\Player;

/**
 * Class Arena
 * @package OneVsOne\Arena
 */
class Arena {

    /** @var Main $plugin */
    public $plugin;

    /** @var  ArenaListener $arenaListener */
    public $arenaListener;

    /** @var  Arena */
    public $arenaScheduler;

    /** @var  string $name */
    public $name;

    /**
     * @var $pos1 Position
     * @var $pos2 Position
     */
    public $pos1, $pos2;

    /** @var  Player[] $players */
    public $players = [];

    /**
     * @var int $phase
     *
     * 0 => lobby
     * 1 => (full)
     * 2 => ingame
     * 3 => (restart)
     */
    public $phase = 0;

    /** @var  int $time */
    public $time = 0;

    /**
     * Arena constructor.
     * @param Main $plugin
     * @param string $name
     * @param Position $pos1
     * @param Position $pos2
     */
    public function __construct(Main $plugin, string $name, Position $pos1, Position $pos2) {
        $this->plugin = $plugin;
        $this->name = $name;
        $this->pos1 = $pos1;
        $this->pos2 = $pos2;
        $this->players = [];
        $this->plugin->getServer()->getPluginManager()->registerEvents($this->arenaListener = new ArenaListener($this), $this->plugin);
        $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask($this->arenaScheduler = new ArenaScheduler($this), 20);
    }

    /**
     * @param Player $player
     */
    public function teleportToArena(Player $player) {

    }
}