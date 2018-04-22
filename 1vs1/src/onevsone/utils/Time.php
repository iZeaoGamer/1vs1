<?php

declare(strict_types=1);

namespace onevsone\utils;

/**
 * Class Time
 * @package onevsone\utis
 */
class Time {

    /**
     * @param int $time
     * @return string
     */
    public static function calculateTime(int $time): string {
        $min = (int)$time/60;
        if(!is_int($min)) {
            $min = intval($min);
        }
        $min = strval($min);
        if(strlen($min) == 0) {
            $min = "00";
        }
        elseif(strlen($min) == 1) {
            $min = "0{$min}";
        }
        else {
            $min = strval($min);
        }
        $sec = $time%60;
        if(!is_int($sec)) {
            $sec = intval($sec);
        }
        $sec = strval($sec);
        if(strlen($sec) == 0) {
            $sec = "00";
        }
        elseif(strlen($sec) == 1) {
            $sec = "0{$sec}";
        }
        else {
            $sec = strval($sec);
        }
        if($time <= 0) {
            return "00:00";
        }
        return strval($min.":".$sec);
    }
}
