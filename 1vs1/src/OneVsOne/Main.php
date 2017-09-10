<?php

namespace OneVsOne;

use OneVsOne\Arena\Arena;
use OneVsOne\Command\OneVsOneCommand;
use OneVsOne\Event\SetupListener;
use OneVsOne\Util\ConfigManager;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;

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
            $this->getServer()->getPluginManager()->registerEvents($this->setupListener = new SetupListener($this), $this);
            $this->getServer()->getCommandMap()->register("1vs1", $this->oneVsOneCommand = new OneVsOneCommand());
            // test arena
            $this->getServer()->loadLevel("1vs1");
            $colors = $this->arenas["Colors"] = new Arena($this, "Colors", new Position(252,4,243, $this->getServer()->getLevelByName("1vs1")),
                new Position(264, 5, 231), 0);
            $colors->signpos = new Position(13, 67, 6, $this->getServer()->getLevelByName("Lobby"));
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