<?php

namespace OneVsOne\Arena;

use OneVsOne\Main;
use OneVsOne\Util\ConfigManager;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
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

    /** @var  ArenaScheduler $arenaScheduler */
    public $arenaScheduler;

    /** @var  string $name */
    public $name;

    /** @var  Position|null $signpos */
    public $signpos;

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
    public $startTime = 31, $gameTime = 301, $restartTime = 16;

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
        if(empty($this->players[0])) {
            $player->teleport($this->pos1);
        }
        elseif(empty($this->players[1])) {
            $player->teleport($this->pos2);
        }
        else {
            $player->sendMessage("§cArenas are full");
        }
        $this->players[0] = $player;
        if(count($this->players) > 1) {
            foreach ($this->players as $players) {
                $players->sendMessage("§7[2/2] §aPlayer {$player->getName()} joined.");
            }
        }
        else {
            $player->sendMessage("§7[1/2] §aPlayer {$player->getName()} joined.");
        }

    }
}