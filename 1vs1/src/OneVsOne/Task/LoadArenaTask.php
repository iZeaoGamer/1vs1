<?php

namespace OneVsOne\Task;

use OneVsOne\Arena\Arena;
use OneVsOne\Util\ConfigManager;
use pocketmine\level\Position;
use pocketmine\utils\Config;

/**
 * Class LoadArenaTask
 * @package OneVsOne\Task
 */
class LoadArenaTask extends OneVsOneTask {

    /** @var  string $name */
    public $name;

    /**
     * LoadArenaTask constructor.
     * @param string $name
     */
    public function __construct(string $name) {
        $this->name = $name;
    }

    public function onRun(int $currentTick) {
        $config = new Config(ConfigManager::getDataFolder()."arenas/{$this->name}.yml", Config::YAML);
        $pos1 = (array)$config->get("pos1");
        if($this->getServer()->isLevelGenerated(strval($pos1[3]))) $this->getPlugin()->getServer()->loadLevel(strval($pos1[3]));
        $pos2 = (array)$config->get("pos2");
        if($this->getServer()->isLevelGenerated(strval($pos2[3]))) $this->getPlugin()->getServer()->loadLevel(strval($pos2[3]));
        $signPos = (array)$config->get("signpos");
        if($this->getServer()->isLevelGenerated(strval($signPos[3]))) $this->getPlugin()->getServer()->loadLevel(strval($signPos[3]));
        $arena = $this->getPlugin()->arenas[$this->name] = new Arena($this->getPlugin(), $this->name, $this->getPlugin()->getServer()->getDefaultLevel()->getSpawnLocation(), $this->getServer()->getDefaultLevel()->getSpawnLocation());
        $arena->name = $this->name;
        $arena->pos1 = new Position(intval($pos1[0]), intval($pos1[1]), intval($pos1[2]), $this->getPlugin()->getServer()->getLevelByName(strval($pos1[3])));
        $arena->pos2 = new Position(intval($pos2[0]), intval($pos2[1]), intval($pos2[2]), $this->getPlugin()->getServer()->getLevelByName(strval($pos2[3])));
        $arena->signpos = new Position(intval($signPos[0]), intval($signPos[1]), intval($signPos[2]), $this->getPlugin()->getServer()->getLevelByName(strval($signPos[3])));
        $this->getPlugin()->getLogger()->notice("Loading arena {$this->name}...");
        $this->onCancel();
        #var_dump($arena);
        #sleep(10);
    }
}