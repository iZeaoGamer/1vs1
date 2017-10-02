<?php

namespace OneVsOne\Task;

use OneVsOne\Main;
use pocketmine\scheduler\Task;
use pocketmine\Server;

/**
 * Class OneVsOneTask
 * @package OneVsOne\task
 */
abstract class  OneVsOneTask extends Task {

    /**
     * @return Main
     */
    public function getPlugin() {
        return Main::getInstance();
    }

    /**
     * @return Server
     */
    public function getServer() {
        return Server::getInstance();
    }
}