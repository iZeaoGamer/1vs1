<?php

namespace OneVsOne;

use OneVsOne\Arena\Arena;

/**
 * Class ArenaManager
 * @package OneVsOne
 */
class ArenaManager {

    /**
     * @return Arena[] $arenas
     */
    public static function getArenas():array {
        return Main::getInstance()->arenas;
    }

    /**
     * @return string[] $arenas
     */
    public static function getArenasNames():array  {
       $arenas = [];
       foreach (self::getArenas() as $name => $arena) {
           array_push($arenas, $name);
       }
       return $arenas;
    }
}