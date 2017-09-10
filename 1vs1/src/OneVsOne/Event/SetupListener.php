<?php

namespace OneVsOne\Event;

use OneVsOne\Main;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

/**
 * Class SetupListener
 * @package OneVsOne\Event
 */
class SetupListener implements Listener {

    /** @var array $players */
    public $players = [];

    /** @var  Main $plugin */
    public $plugin;

    /** @var  array $updates */
    public $updates;

    /**
     * SetupListener constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->players = [];
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        if(isset($this->players[strtolower($player->getName())])) {
            $message = $event->getMessage();
            $args = explode(" ", strtolower($message));
            $arena = $this->plugin->arenas[$this->players[strtolower($player->getName())]];
            switch ($args[0]) {
                case "help":
                    $player->sendMessage("§7-- == [ 1vs1 Setup Help ] == --\n".
                    "§7setpos : set spawn position\n".
                    "§7updatesign : update arena sign\n".
                    "§7done : leave setup mode\n".
                    "§7enable : enable arena");
                    break;
                case "setpos":
                    switch (strval($args[1])) {
                        case "1":
                            $arena->pos1 = $player->getPosition();
                            $player->sendMessage("§aPosition updated");
                            break;
                        case "2":
                            $arena->pos2 = $player->getPosition();
                            $player->sendMessage("§aPosition updated");
                            break;
                        default:
                            $player->sendMessage("§cThere are only 2 positions.");
                            break;
                    }
                    break;
                case "updatesign":
                    $this->updates[strtolower($player->getName())] = 1;
                    $player->sendMessage("§aBreak the sign to complete setup!");
                    break;
                case "done":
                    unset($this->players[strtolower($player->getName())]);
                    $player->sendMessage("§aSuccessfully leaved setup mode!");
                    break;
                case "enable":
                    $arena->phase = 1;
                    $player->sendMessage("§aArena enabled.");
                    break;
                default:
                    $player->sendMessage("§7Use §8help §7to display setup commands, §8done §7to leave setup mode.");
                    break;
            }
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $arena = $this->plugin->arenas[$this->players[strtolower($player->getName())]];
        if(isset($this->updates[strtolower($player->getName())]) && $this->updates[strtolower($player->getName())] == 1) {
            if($event->getBlock()->getId() == Block::STANDING_SIGN) {
                $player->sendMessage("§aSign successfully set.");
                $arena->signpos = $event->getBlock()->asPosition();
                unset($this->updates[strtolower($player->getName())]);
                $event->setCancelled(true);
            }
            else {
                $player->sendMessage("§cBlock is not sign.");
            }
        }
    }
}