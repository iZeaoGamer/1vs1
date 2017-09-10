<?php

namespace OneVsOne\Command;

use OneVsOne\Arena\Arena;
use OneVsOne\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

/**
 * Class OneVsOneCommand
 * @package OneVsOne\Command
 */
class OneVsOneCommand extends Command implements PluginIdentifiableCommand {

    /** @var  Main $plugin */
    public $plugin;

    /**
     * OneVsOneCommand constructor.
     * @param string $name
     * @param string $description
     * @param null $usageMessage
     * @param array $aliases
     */
    public function __construct($name = "1vs1", $description = "1vs1 commands", $usageMessage = null, $aliases = ["1v1", "11"]) {
        $this->plugin = Main::getInstance();
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!($sender instanceof Player)) {
            $sender->sendMessage("§cUse this command in game!");
            return;
        }
        if(empty($args[0])) {
            $sender->sendMessage("§cUsage: §7/1vs1 help");
            return;
        }
        $perms = strtolower($args[0]);
        if(!$sender->hasPermission("1vs1.cmd.{$perms}")) {
            $sender->sendMessage("§cYou have not permissions to use this command!");
            return;
        }
        switch (strtolower($args[0])) {
            case "help":
                if(!$sender->hasPermission("1vs1.cmd.help")) {
                    $sender->sendMessage("§cYou have not permissions to use this command");
                    return;
                }
                $sender->sendMessage("§7-- == [ 1vs1 ] == --\n".
                    "§7/1vs1 addarena : add arena\n".
                    "§7/1vs1 set : set arena\n".
                    "§7/1vs1 leave : leave arena");
                return;
            case "addarena":
                if(!$sender->hasPermission("1vs1.cmd.addarena")) {
                    $sender->sendMessage("§cYou have not permissions to use this command");
                    return;
                }
                if(empty($args[1])) {
                    $sender->sendMessage("§cUsage: §7/1vs1 addarena <arena>");
                    return;
                }
                if(isset($this->plugin->arenas[$args[1]])) {
                    $sender->sendMessage("§cArena {$args[1]} already exists!");
                    return;
                }
                $this->plugin->arenas[$args[1]] = new Arena($this->plugin, $args[1], new Position(0,100,0, $this->getServer()->getDefaultLevel()), new Position(0,100,0, $this->getServer()->getDefaultLevel()), 0);
                $sender->sendMessage("§aTo complete arena setup write /1vs1 set <arena>");
                return;
            case "set":
                if(!$sender->hasPermission("1vs1.cmd.addarena")) {
                    $sender->sendMessage("§cYou have not permissions to use this command");
                    return;
                }
                if(empty($args[1])) {
                    $sender->sendMessage("§cUsage: §7/1vs1 set <arena>");
                    return;
                }
                try {
                    if(!($this->plugin->arenas[$args[1]] instanceof Arena)) {
                        $sender->sendMessage("§cArena {$args[1]} does not exists!");
                        return;
                    }
                }
                catch (\Exception $exception) {
                    $sender->sendMessage("§cArena {$args[1]} does not exists!");
                    return;
                }
                $this->plugin->setupListener->players[strtolower($sender->getName())] = $args[1];
                $sender->sendMessage("§aYou are now in setup mode!");
                return;
            case "setjoinsign":
                if(!$sender->hasPermission("1vs1.cmd.setjoinsign")) {
                    $sender->sendMessage("§cYou have not permissions to use this command");
                    return;
                }
                if(empty($args[1])) {
                    $sender->sendMessage("§cUsage: §7/1vs1 setjoinsign <arena>");
                    return;
                }
                return;
            default:
                $sender->sendMessage("§cUsage: §7/1vs1 help");
                return;

        }
    }

    /**
     * @return Server
     */
    public function getServer(): Server {
        return Server::getInstance();
    }

    public function getPlugin(): Plugin {
        return Main::getInstance();
    }
}