<?php

namespace OneVsOne\Util;

use OneVsOne\Main;
use pocketmine\Server;

/**
 * Class ConfigManager
 * @package OneVsOne\Util
 */
class ConfigManager {

    /**
     * @return string
     */
    public static function getDataFolder() {
        return Main::getInstance()->getDataFolder();
    }

    /**
     * @return string
     */
    public static function getDataPath() {
        return Server::getInstance()->getDataPath();
    }
}