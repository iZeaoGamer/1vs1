<?php

declare(strict_types=1);

namespace onevsone;

use onevsone\arena\Arena;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class OneVsOne extends PluginBase implements Listener {

    /** @var OneVsOne $instance */
    private static $instance;

    /** @var ArenaManager $arenaMgr */
    public $arenaMgr;

    /** @var array $setters */
    public $setters = [];

    public function onEnable() {
        self::$instance = $this;
        $this->initConfig();
        $this->arenaMgr = new \OneVsOne\ArenaManager($this);
    }

    public function initConfig() {
        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_dir($this->getDataFolder()."arenas")) {
            @mkdir($this->getDataFolder()."arenas");
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if(!$sender instanceof Player) {
            return false;
        }
        if($command->getName() != "1vs1") {
            return false;
        }
        if(!$sender->hasPermission("1vs1.cmd")) {
            $sender->sendMessage("§cYou do not have permissions to use this command!");
            return false;
        }
        if(empty($args[0])) {
            $sender->sendMessage(self::getPrefix()."§cUsage: §7/1vs1 help");
            return false;
        }
        switch ($args[0]) {
            case "help":
                $sender->sendMessage("§7--- == [ §61vs1 §7] == ---\n".
                "§b/1vs1 help §7Displays help\n".
                "§b/1vs1 create §7Create 1vs1 arena\n".
                "§b/1vs1 set §7Manage with 1vs1 arena");
                break;
            case "create":
                if(empty($args[1])) {
                    $sender->sendMessage("§cUsage: §7/1vs1 create <name>");
                    return false;
                }
                if($this->arenaMgr->arenaExists($args[1])) {
                    $sender->sendMessage(self::getPrefix()."§cArena {$args[1]} already exists!");
                    return false;
                }
                $this->arenaMgr->createArena($args[1]);
                $sender->sendMessage(self::getPrefix()."§aArena {$args[1]} created! Use /1vs1 set <{$args[1]}> to set it");
                return false;
            case "set":
                if(empty($args[1])) {
                    $sender->sendMessage("§cUsage: §7/1vs1 set <arena>");
                    return false;
                }
                if(!$this->arenaMgr->arenaExists($args[1])) {
                    $sender->sendMessage(self::getPrefix()."§cArena {$args[1]} does not found!");
                    return false;
                }
                $sender->sendMessage("§aYou are now in setup mode, type §7help §afor help, §7done §afor exit.");
                $this->setters[$sender->getName()] = $this->arenaMgr->arenas[$args[1]];
                return false;
        }
        return false;
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        if(empty($this->setters[$player->getName()])) {
            return;
        }
        $args = explode(" ", $event->getMessage());
        $arena = $this->setters[$player->getName()];
        switch ($args[0]) {
            case "help":
                $player->sendMessage("§a1vs1 setup help:\n".
                "§afirst : Set first position\n".
                "§asecond : Set second position\n".
                "§asign : Set Sign");
                break;
            case "first":

            default:
                $player->sendMessage("§aUse §7done §afor exit, §7help §afor help!");
                break;
        }
    }

    /**
     * @return OneVsOne $oneVsOne
     */
    public static function getInstance(): OneVsOne {
        return self::$instance;
    }

    /**
     * @return string
     */
    public static function getPrefix(): string {
        return "§6§l[1vs1] §r§7";
    }


}
