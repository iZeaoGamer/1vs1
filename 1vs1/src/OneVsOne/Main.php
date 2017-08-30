<?php

namespace OneVsOne;

use OneVsOne\Arena\Arena;
use OneVsOne\Arena\ArenaListener;
use OneVsOne\Event\EventListener;
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

    /** @var  ArenaListener $arenaListener */
    public $arenaListener;

    /** @var  ConfigManager $configManager */
    public $configManager;

    /** @var  EventListener $eventListener */
    public $eventListener;

    /** @var  array $waiting */
    public $waiting = [];

    /**
     * 1vs1 onEnable() function
     * @return void
     */
    public function onEnable() {
        $this->configManager = new ConfigManager($this);
        $this->getServer()->getPluginManager()->registerEvents($this->eventListener = new EventListener($this),$this);
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
        return self::$instance->configManager->getConfigData("enable-prefix") == "true" ? self::$instance->configManager->getConfigData("prefix")."§7 " : "§7";
    }

    /**
     * @return Main $plugin
     */
    public static function getInstance():Main {
        return self::$instance;
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args):bool {
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
                    if(!$sender->hasPermission("1vs1.cmd.addarena")) {
                        $sender->sendMessage("§cYou have not permissions to use this command");
                        return false;
                    }
                    if(empty($args[1])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 addarena <arena>");
                        return false;
                    }
                    if(isset($this->arenas[$args[1]])) {
                        $sender->sendMessage("§cArena {$args[1]} already exists!");
                        return false;
                    }
                    $name = $args[1];
                    $sender->sendMessage("§aTo complete arena setup write /1vs1 setpos <1|2> <{$name}>");

                    return false;
                case "setpos":
                    if(!$sender->hasPermission("1vs1.cmd.setpos")) {
                        $sender->sendMessage("§cYou have not permissions to use this command");
                        return false;
                    }
                    if(empty($args[1]) || empty($args[2])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 setpos <pos: 1|2> <arena>");
                        return false;
                    }
                    if(empty($this->waiting[$args[2]])) {
                        $sender->sendMessage("§cArena {$args[2]} does not exists!");
                        return false;
                    }
                    if(!in_array(strval($args[1]), ["1","2"])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 setpos <pos: 1|2> <arena>");
                        return false;
                    }
                    $this->waiting[$args[2]][strval($args[1])] = new Position($sender->getX(), $sender->getY(), $sender->getZ(), $sender->getLevel());
                    $index = strval($args[1]) == "1" ? "2" : "1";
                    if(isset($this->waiting[$args[2]][$index])) {
                        $data1 = $this->waiting[$args[2][strval($args[1])]];
                        $data2 = $this->waiting[$args[2]][$index];
                        if($data2 instanceof Position && $data1 instanceof Position) {
                            if($data2->getLevel()->getName() == $data1->getLevel()->getName()) {
                                $sender->sendMessage("§aArena successfully registered!");
                                $this->arenas[$args[2]] = new Arena($this, $args[2], $data1, $data2);
                            }
                            else {
                                $sender->sendMessage("§aPositions must be in same level");
                            }
                        }
                        else {
                            $sender->sendMessage("§cBUG #1");
                            var_dump($data1);
                            var_dump($data2);
                        }
                    }
                    else {
                        $sender->sendMessage("§a{$args[1]}. position selected, use §7/1vs1 {$index} {$args[2]}§a to finish arena setup.");
                    }
                    return false;
                case "setjoinsign":
                    if(!$sender->hasPermission("1vs1.cmd.setjoinsign")) {
                        $sender->sendMessage("§cYou have not permissions to use this command");
                        return false;
                    }
                    if(empty($args[1])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 setjoinsign <arena>");
                    }
                    return false;

            }
        }
    }
}