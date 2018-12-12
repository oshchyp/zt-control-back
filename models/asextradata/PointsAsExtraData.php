<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:38
 */

namespace app\models\asextradata;


use app\models\Points;
use app\components\models\ModelAsExtraDataInterface;

class PointsAsExtraData extends Points implements ModelAsExtraDataInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
       return [];
    }
}