<?php

namespace OneVsOne\Util;

use OneVsOne\Main;
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

    public function saveAll() {
        foreach ($this->plugin->arenas as $name => $arena) {
            $config = new Config(self::getDataFolder()."areans/{$name}.yml", Config::YAML);
            $config->set("pos1", serialize($arena->pos1));
            $config->set("pos2", serialize($arena->pos2));
            $config->set("signpos", serialize($arena->signpos));
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