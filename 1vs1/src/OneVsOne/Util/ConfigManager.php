<?php

namespace OneVsOne\Util;

use OneVsOne\Main;
use pocketmine\Server;

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