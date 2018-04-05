<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 4/5/18
 * Time: 3:11 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalyzer\helpers;


class ConsoleHelper
{
    const FOREGROUND_GREEN = '0;32';
    const BACKGROUND_YELLOW = '43';
    const BACKGROUND_GREEN = '42';

    /**
     * @param $string
     * @param null $foreground_color
     * @param null $backgroundColor
     * @return string
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function getColoredString($string, $foreground_color = null, $backgroundColor = null) {
        $coloredString = "";

        if ($foreground_color) {
            $coloredString .= "\033[" . $foreground_color . "m";
        }

        if (isset($backgroundColor)) {
            $coloredString .= "\033[" . $backgroundColor . "m";
        }

        $coloredString .=  $string . "\033[0m";

        return $coloredString;
    }
}