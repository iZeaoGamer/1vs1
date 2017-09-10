<?php

namespace OneVsOne;

use OneVsOne\Arena\Arena;
use OneVsOne\Arena\ArenaListener;
use OneVsOne\Command\OneVsOneCommand;
use OneVsOne\Event\EventListener;
use OneVsOne\Event\SetupListener;
use OneVsOne\Util\ConfigManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

/**
 * Class Main
 * @package OneVsOne
 */
class Main extends PluginBase {

    /** @var  Main $plugin */
    static $instance;

    /** @var  Arena[] $arenas */
    public $arenas;

    /** @var  ConfigManager $configManager */
    public $configManager;

    /** @var  EventListener $eventListener */
    public $eventListener;

    /** @var  SetupListener $setupListener */
    public $setupListener;

    /** @var  OneVsOneCommand $oneVsOneCommand */
    public $oneVsOneCommand;

    /**
     * 1vs1 onEnable() function
     * @return void
     */
    public function onEnable() {
        try {
            self::$instance = $this;
            $this->configManager = new ConfigManager($this);
            $this->configManager->init();
            $this->getServer()->getPluginManager()->registerEvents($this->setupListener = new SetupListener($this), $this);
            $this->configManager->loadArenas();
            $this->getServer()->getCommandMap()->register("1vs1", $this->oneVsOneCommand = new OneVsOneCommand());
        }
        catch (\Exception $exception) {
            $this->getLogger()->debug("§cPlugin se nepodarilo nacist");
            $this->getLogger()->debug($exception->getMessage()." line ". $exception->getLine() . "file " . $exception->getFile());
        }
    }

    public function onDisable() {
        $this->configManager->saveAll();
    }

    /**
     * @return string $prefix
     */
    public static function getPrefix():string {
        return self::$instance->configManager->getConfigData("enable-prefix") == "true" ? self::$instance->configManager->getConfigData("prefix")."§7 " : "§7";
    }

    /**
     * @return Main $plugin
     */
    public static function getInstance():Main {
        return self::$instance;
    }
}