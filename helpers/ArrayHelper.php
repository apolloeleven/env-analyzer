<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 4/5/18
 * Time: 1:39 PM
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace apollo11\envAnalyzer\helpers;


class ArrayHelper
{
    /**
     * @param $distArray []
     * @param $array []
     * @return array []
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public static function getMissingValues($distArray, $array)
    {
        $returnArray = [];
        foreach ($distArray as $environmentDistKey => $environmentDistValue) {
            if(!key_exists($environmentDistKey,$array)) {
                $returnArray[$environmentDistKey] = $environmentDistValue;
            }
        }
        return $returnArray;
    }
}