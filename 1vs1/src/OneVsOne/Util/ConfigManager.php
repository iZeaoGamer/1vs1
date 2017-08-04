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

    /** @var  Config $config */
    public $config;

    /** @var  Config $data */
    public $data;

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

    /**
     * @return Config $config
     */
    public static function getConfig():Config {
        return DataManager::get()->getConfig();
    }

    /**
     * @return Config $data
     */
    public static function getData():Config {
        return DataManager::get()->getData();
    }
}