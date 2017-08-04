<?php

namespace OneVsOne;

use OneVsOne\Arena\Arena;
use OneVsOne\Arena\ArenaListener;
use OneVsOne\Arena\JoinNPC;
use OneVsOne\Util\ConfigManager;
use OneVsOne\Util\DataManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

/**
 * Class Main
 * @package OneVsOne
 */
class Main extends PluginBase {

    /** @var  Main $plugin */
    static $instance;

    /** @var  Arena $arena */
    public $arena;

    /** @var  ArenaListener $arenaListener */
    public $arenaListener;

    /** @var  ConfigManager $configManager */
    public $configManager;

    /** @var  DataManager $dataManager */
    public $dataManager;

    /**
     * 1vs1 onEnable() function
     * @return void
     */
    public function onEnable() {
        $this->arena = new Arena($this);
        $this->arenaListener = new ArenaListener($this, $this->arena);
        $this->configManager = new ConfigManager();
        $this->dataManager = new DataManager($this);

        $this->getServer()->getPluginManager()->registerEvents(self::getArenaListner(), $this);


        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_file($this->getDataFolder()."/config.yml")) {
            $this->saveResource("/config.yml");
            $this->getLogger()->debug("§aConfig not found, creating new config.");
        }
        if(!is_file($this->getDataFolder()."/arenas.yml")) {
            $this->saveResource("/arenas.yml");
            $this->getLogger()->debug("§aData not found, creating new data.");
        }
    }

    /**
     * @return string $prefix
     */
    public static function getPrefix():string {
        return strval(ConfigManager::getConfig()->get("enable-prefix")) == "true" ? ConfigManager::getConfig()->get("prefix")." " : "";
    }

    /**
     * @return Main $plugin
     */
    public static function getInstance():Main {
        return self::$instance;
    }

    /**
     * @return Arena $arena
     */
    public static function getArena():Arena {
        return self::getInstance()->arena;
    }

    /**
     * @return ArenaListener $arenaListener
     */
    public static function getArenaListner():ArenaListener {
        return self::getInstance()->arenaListener;
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        $cmd = strtolower($command->getName());
        if(!($sender instanceof Player)) {
            $sender->sendMessage("§cUse this command in game!");
            return false;
        }
        if($cmd == "1vs1") {
            if(empty($args[0])) {
                $sender->sendMessage("§cUsage: §7/1vs1 help");
                return false;
            }
            $perms = strtolower($args[0]);
            if(!$sender->hasPermission("1vs1.cmd.{$perms}")) {
                $sender->sendMessage("§cYou have not permissions to use this command!");
                return false;
            }
            switch (strtolower($args[0])) {
                case "help":

                    return false;
                case "addarena":
                    if(empty($args[1])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 addarena <arena>");
                        return false;
                    }
                    return false;
                case "setradius":
                    if(empty($args[1])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 setradius <radius>");
                        return false;
                    }
                    if(!is_numeric($args[1])) {
                        $sender->sendMessage("§cRadius must be numeric!");
                        return false;
                    }
                    return false;
                case "reload":

                    return false;
                case "joinnpc":
                    $npc = new JoinNPC($this, $this->arena, $sender);
                    $npc->spawn();
                    return false;

            }
        }
    }
}