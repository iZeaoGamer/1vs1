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
        return Main::getInstance()->getConfig();
    }

    /**
     * @return Config $data
     */
    public static function getData():Config {
        return new Config(self::getDataFolder()."/arenas.yml", Config::YAML);
    }
}