<?php

declare(strict_types=1);

namespace onevsone\arena;

use onevsone\OneVsOne;
use onevsone\utis\Time;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\tile\Sign;

/**
 * Class ArenaScheduler
 * @package onevsone\arena
 */
class ArenaScheduler extends Task {

    /** @var Arena $plugin */
    public $plugin;

    /**
     * ArenaScheduler constructor.
     * @param Arena $arena
     */
    public function __construct(Arena $arena) {
        $this->plugin = $arena;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick) {
        if($this->plugin->phase === 0) {
            $this->lobby();
        }
        elseif ($this->plugin->phase === 1) {
            $this->game();
        }
        else {
            $this->restart();
        }
        $this->refreshSign();
    }

    public function refreshSign() {
        $data = $this->plugin->config["sign"];
        if(!$this->plugin->plugin->getServer()->isLevelGenerated($data[3])) {
            return;
        }
        $pos = new Position($data[0], $data[1], $data[2], $this->plugin->plugin->getServer()->getLevelByName($data[3]));
        $sign = $pos->getLevel()->getTile($pos);
        if(!$sign instanceof Sign) {
            return;
        }

        /** @var int $status */
        $status = $this->plugin->phase === 0 ? (count($this->plugin->getPlayers()) == 2 ? 1 : 0) : $this->plugin->phase;

        $sign->setLine(0, Arena::SIGN_PREFIX, true);
        $sign->setLine(1, str_replace("%p", count($this->plugin->getPlayers()), Arena::SIGN_SLOTS), true);
        $sign->setLine(2, Arena::SIGN_STATUS[$status], true);
        $sign->setLine(3, str_replace("%m", $this->plugin->config["level"], Arena::SIGN_MAP), true);
    }

    public function lobby() {

        if(($player1 = $this->plugin->player1) instanceof Player && ($player2 = $this->plugin->player2) instanceof Player) {
            foreach ($this->plugin->getPlayers() as $player) {
                if($player instanceof Player) {
                    $player2->sendTip(OneVsOne::getPrefix()."§f|| §cWait for players.");
                }
            }
        }

        else {

            /** @var Player $player */
            foreach ($this->plugin->getPlayers() as $player) {
                $player->sendTip(OneVsOne::getPrefix()."§f|| §6Starting in {$this->plugin->startTime} sec!");
            }

            $this->plugin->startTime--;

            if($this->plugin->startTime <= 0) {
                $this->plugin->phase = 1;
            }
        }
    }

    public function game() {
        foreach ($this->plugin->getPlayers() as $player) {
            $player->addTitle(
                str_repeat(" ", 60). "§6--- == [ 1vs1 ] == ---\n".
                     str_repeat(" ", 60). "§7PvP Time: ".Time::calculateTime($this->plugin->gameTime)." sec!"
            );
        }
    }

    public function restart() {
        foreach ($this->plugin->getPlayers() as $player) {
            $player->sendTip(OneVsOne::getPrefix()."§f|| §aRestarting in {$this->plugin->restartTime} sec!");
        }
        if($this->plugin->restartTime <= 0) {
            foreach ($this->plugin->getPlayers() as $player) {
                $player->teleport($this->plugin->plugin->getServer()->getDefaultLevel()->getSafeSpawn());
                $player->setGamemode($this->plugin->plugin->getServer()->getDefaultGamemode());
                $player->setHealth(20);
                $player->setFood(20);
                $player->setXpLevel(0);
                $player->setXpProgress(0);
            }
            $this->plugin->loadGame();
        }
    }
}