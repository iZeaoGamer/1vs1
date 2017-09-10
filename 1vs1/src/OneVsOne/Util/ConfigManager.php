<?php

namespace OneVsOne\Util;

use OneVsOne\Arena\Arena;
use OneVsOne\Main;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\utils\Config;

/**
 * Class ConfigManager
 * @package OneVsOne\Util
 */
class ConfigManager {

    /** @var  Main $plugin */
    public $plugin;

    /** @var  array $data */
    public $configData;

    /**
     * ConfigManager constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->init();
        $this->loadArenas();
    }

    public function init() {
        if(!is_dir(self::getDataFolder())) {
            @mkdir(self::getDataFolder());
        }
        if(!is_file(self::getDataFolder()."/config.yml")) {
            $this->plugin->saveResource("/config.yml");
        }
        if(!is_dir(self::getDataFolder()."arenas")) {
            @mkdir(self::getDataFolder()."arenas");
        }
        $this->configData = $this->plugin->getConfig()->getAll();
    }

    public function loadArenas() {
        foreach (glob(self::getDataFolder()."arenas/*.yml") as $arenaData) {
            $this->plugin->getLogger()->debug("§aLoading {$arenaData} arena...");
        }
    }

    /**
     * @param string $fileName
     */
    final function loadArena(string $fileName) {
        $name = basename($fileName, ".yml");
        $config = new Config(self::getDataFolder()."arenas/{$name}.yml", Config::YAML);
        $arena = $this->plugin->arenas[$name] = new Arena($this->plugin, $name, $this->plugin->getServer()->getDefaultLevel()->getSpawnLocation(), $this->plugin->getServer()->getDefaultLevel()->getSpawnLocation());
        $arena->name = $name;
        $pos1 = (array)$config->get("pos1");
        $pos2 = (array)$config->get("pos2");
        $signPos = (array)$config->get("signpos");
        $arena->pos1 = new Position(intval($pos1[0]), intval($pos1[1]), intval($pos1[2]), $this->plugin->getServer()->getLevelByName($pos1[3]));
        $arena->pos2 = new Position(intval($pos2[0]), intval($pos2[1]), intval($pos2[2]), $this->plugin->getServer()->getLevelByName($pos2[3]));
        $arena->signpos = new Position(intval($signPos[0]), intval($signPos[1]), intval($signPos[2]), $this->plugin->getServer()->getLevelByName($signPos[3]));
    }

    public function saveAll() {
        foreach ($this->plugin->arenas as $name => $arena) {
            $config = new Config(self::getDataFolder()."arenas/{$name}.yml", Config::YAML);
            $config->set("pos1", [$arena->pos1->getX(), $arena->pos1->getY(), $arena->pos1->getZ(), $arena->pos1->getLevel()->getName()]);
            $config->set("pos2", [$arena->pos2->getX(), $arena->pos2->getY(), $arena->pos2->getZ(), $arena->pos2->getLevel()->getName()]);
            $config->set("signpos", [$arena->signpos->getX(), $arena->signpos->getY(), $arena->signpos->getZ(), $arena->signpos->getLevel()->getName()]);
            $config->save();
        }
    }

    /**
     * @param string|mixed $data
     * @return string
     */
    public function getConfigData(string $data):string {
        return strval($this->configData[$data]);
    }

    /**
     * @return string $dataFolder
     */
    public static function getDataFolder():string {
        return Main::getInstance()->getDataFolder();
    }

    /**
     * @return string $dataPath
     */
    public static function getDataPath():string {
        return Server::getInstance()->getDataPath();
    }
}