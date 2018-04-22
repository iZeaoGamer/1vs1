<?php

declare(strict_types=1);

namespace OneVsOne;

use onevsone\arena\Arena;
use pocketmine\utils\Config;

/**
 * Class ArenaManager
 * @package onevsone
 */
class ArenaManager {

    /** @var OneVsOne $plugin */
    public $plugin;

    /** @var array $arenas */
    public $arenas = [];

    public function __construct(OneVsOne $plugin) {
        $this->plugin = $plugin;
        $this->loadArenas();
    }

    public function loadArenas() {
        foreach (glob($this->plugin->getDataFolder()."arenas/*.yml") as $file) {
            $config = new Config($file, Config::YAML);
            $this->arenas[basename($file, ".yml")] = new Arena($this, $config->getAll());
        }
    }

    public function arenaExists(string $name):bool {
        return boolval(isset($this->arenas[$name]));
    }

    public function createArena(string $name) {
        $this->arenas[$name] = new Arena($this, [
            "level" => null,
            "sign" => [],
            "spawn" => [
                "1" => [],
                "2" => []
            ]
        ]);
    }

}
