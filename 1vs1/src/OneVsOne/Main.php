<?php

namespace OneVsOne;

use OneVsOne\Arena\Arena;
use OneVsOne\Arena\ArenaListener;
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

    /**
     * 1vs1 onEnable() function
     * @return void
     */
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(self::getArenaListner(), $this);
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
                        return false;
                    }

                    return false;

            }
        }
    }
}