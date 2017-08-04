<?php

namespace OneVsOne\Util;

use OneVsOne\Main;
use pocketmine\utils\Config;

class DataManager {

    /** @var  DataManager $dataManager */
    static $dataManager;

    /** @var  Main $plugin */
    public $plugin;

    /** @var  Config $config */
    public $config;

    /** @var  Config $data */
    public $data;

    /**
     * DataManager constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @return DataManager $dataManager
     */
    public static function get() {
        return self::$dataManager;
    }

    /**
     * @return Config $config
     */
    public function getConfig():Config {
        if($this->config instanceof Config) {
            return $this->config;
        } else {
            $this->config = Main::getInstance()->getConfig();
        }
    }

    /**
     * @return Config
     */
    public function getData():Config {
        if($this->data instanceof Config) {
            return $this->data;
        } else {
            $this->data = new Config(ConfigManager::getDataFolder()."/arenas.yml", Config::YAML);
        }
    }
}