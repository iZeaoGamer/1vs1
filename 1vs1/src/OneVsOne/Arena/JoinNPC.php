<?php

namespace OneVsOne\Arena;

use OneVsOne\Main;
use pocketmine\entity\Human;
use pocketmine\inventory\PlayerInventory;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;

/**
 * Class JoinNPC
 * @package OneVsOne\Arena\JoinNPC
 * @author GamakCZ
 */
class JoinNPC {

    /** @var  Player $player */
    public $player;

    /** @var  Main $plugin */
    public $plugin;

    /** @var  Arena $arena */
    public $arena;

    /**
     * JoinNPC constructor.
     * @param Main $plugin
     * @param Arena $arena
     * @param Player $player
     */
    public function __construct(Main $plugin, Arena $arena, Player $player) {
        $this->player = $player;
        $this->plugin = $plugin;
        $this->arena = $arena;
    }

    /**
     * @return PlayerInventory
     */
    public function getInventory():PlayerInventory {
        return $this->player->getInventory();
    }

    /**
     * @return Level $level
     */
    public function getLevel():Level {
        return $this->player->getLevel();
    }

    /**
     * @return CompoundTag $nbt
     */
    public function getNBT():CompoundTag {
        $player = $this->player;
        $nbt = new CompoundTag("", [
                "Pos" => new ListTag("Pos", [
                    new DoubleTag("", $player->getX()),
                    new DoubleTag("", $player->getY()),
                    new DoubleTag("", $player->getZ())
                ]),
                "Motion" => new ListTag("Motion", [
                    new DoubleTag("", 0),
                    new DoubleTag("", 0),
                    new DoubleTag("", 0)
                ]),
                "Rotation" => new ListTag("Rotation", [
                    new FloatTag("", 360),
                    new FloatTag("", 0)
                ]),
                "Skin" => new CompoundTag("Skin", [
                    "Data" => new StringTag("Data", $player->getSkinData()),
                    "Name" => new StringTag("Name", $player->getSkinId())
                ])
            ]
        );
        return $nbt;
    }

    /**
     * @return void
     */
    public function spawn() {
        $human = new Human($this->getLevel(), $this->getNBT());
        $humanInv = $human->getInventory();
        $playerInv = $this->getInventory();
        $humanInv->setItemInHand($playerInv->getItemInHand());
        $humanInv->setBoots($playerInv->getBoots());
        $humanInv->setLeggings($playerInv->getLeggings());
        $humanInv->setChestplate($playerInv->getChestplate());
        $humanInv->setHelmet($playerInv->getHelmet());
        $human->spawnTo($this->player);
    }
}