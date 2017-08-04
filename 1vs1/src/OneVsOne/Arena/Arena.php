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

    /** @var  array $arenas */
    public $arenas;

    /** @var  string $names */
    public $players;

    /** @var  Main */
    public $plugin;

    /**
     * Arena constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @return void
     */
    public function reloadData() {
        $data = ConfigManager::getData();

    }

    /**
     * @param string $name
     * @param Position $pos
     */
    public function addArena(string $name, Position $pos) {
        $data = ConfigManager::getData();
        if($this->arenaExists($name)) {
            $this->arenas[$name]["pos"] = $pos;
            $data->set($name, serialize($this->arenas[$name]));
            $data->save();
        }
    }

    /**
     * @param string $arena
     * @return bool $bool
     */
    public function arenaExists(string $arena):bool {
        return isset($this->arenas[$arena]) ? true : false;
    }

    /**
     * @param Player $player
     * @return bool $bool
     */
    public function inGame(Player $player):bool {
        $name = $player->getName();
        return $this->players[$player->getName()] == "ingame" ? true : false;
    }
}