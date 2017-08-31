<?php

namespace OneVsOne;

use OneVsOne\Arena\Arena;
use OneVsOne\Arena\ArenaListener;
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
                    if(!$sender->hasPermission("1vs1.cmd.help")) {
                        $sender->sendMessage("§cYou have not permissions to use this command");
                        return false;
                    }
                    $sender->sendMessage("§7-- == [ 1vs1 ] == --\n".
                    "§7/1vs1 addarena : add arena\n".
                    "§7/1vs1 setpos : set arena join pos\n".
                    "§7/1vs1 setsignpos : set joinsign pos");
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
                    $this->arenas[$args[1]] = new Arena($this, $args[1], new Position(0,100,0, $this->getServer()->getDefaultLevel()), new Position(0,100,0, $this->getServer()->getDefaultLevel()), 0);
                    $sender->sendMessage("§aTo complete arena setup write /1vs1 set <arena>");
                    return false;
                case "set":
                    if(!$sender->hasPermission("1vs1.cmd.addarena")) {
                        $sender->sendMessage("§cYou have not permissions to use this command");
                        return false;
                    }
                    if(empty($args[1])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 set <arena>");
                        return false;
                    }
                    try {
                        if(!($this->arenas[$args[1]] instanceof Arena)) {
                            $sender->sendMessage("§cArena {$args[1]} does not exists!");
                            return false;
                        }
                    }
                    catch (\Exception $exception) {
                        $sender->sendMessage("§cArena {$args[1]} does not exists!");
                        return false;
                    }
                    $this->setupListener->players[strtolower($sender->getName())] = $args[1];
                    $sender->sendMessage("§aYou are now in setup mode!");
                    return false;
                case "setjoinsign":
                    if(!$sender->hasPermission("1vs1.cmd.setjoinsign")) {
                        $sender->sendMessage("§cYou have not permissions to use this command");
                        return false;
                    }
                    if(empty($args[1])) {
                        $sender->sendMessage("§cUsage: §7/1vs1 setjoinsign <arena>");
                        return false;
                    }
                    return false;

            }
        }
    }
}