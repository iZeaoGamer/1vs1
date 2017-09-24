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
        if(file_exists(self::getDataFolder()."arenas")) {
            foreach (glob(self::getDataFolder()."arenas/*.yml") as $arenaData) {
                $this->plugin->getLogger()->notice("§aLoading {$arenaData} arena...");
                $this->loadArena($arenaData);
            }
        }
    }

    /**
     * @param string $fileName
     */
    public function loadArena(string $fileName) {

        $name = basename($fileName, ".yml");

        $config = new Config(self::getDataFolder()."arenas/{$name}.yml", Config::YAML);

        $pos1 = (array)$config->get("pos1");

        if($this->plugin->getServer()->isLevelGenerated(strval($pos1[3]))) $this->plugin->getServer()->loadLevel(strval($pos1[3]));

        $pos2 = (array)$config->get("pos2");

        if($this->plugin->getServer()->isLevelGenerated(strval($pos2[3]))) $this->plugin->getServer()->loadLevel(strval($pos2[3]));

        $signPos = (array)$config->get("signpos");

        if($this->plugin->getServer()->isLevelGenerated(strval($signPos[3]))) $this->plugin->getServer()->loadLevel(strval($signPos[3]));

        $arena = $this->plugin->arenas[$name] = new Arena($this->plugin, $name, $this->plugin->getServer()->getDefaultLevel()->getSpawnLocation(), $this->plugin->getServer()->getDefaultLevel()->getSpawnLocation());

        $arena->name = $name;

        $arena->pos1 = new Position(intval($pos1[0]), intval($pos1[1]), intval($pos1[2]), $this->plugin->getServer()->getLevelByName(strval($pos1[3])));

        $arena->pos2 = new Position(intval($pos2[0]), intval($pos2[1]), intval($pos2[2]), $this->plugin->getServer()->getLevelByName(strval($pos2[3])));

        $arena->signpos = new Position(intval($signPos[0]), intval($signPos[1]), intval($signPos[2]), $this->plugin->getServer()->getLevelByName(strval($signPos[3])));

        var_dump($arena);

        sleep(10);
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