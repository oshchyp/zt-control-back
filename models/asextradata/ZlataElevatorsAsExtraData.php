<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.01.2019
 * Time: 14:48
 */

namespace app\models\asextradata;


use app\components\models\ModelAsExtraDataInterface;
use app\models\ZlataElevators;

class ZlataElevatorsAsExtraData extends ZlataElevators implements ModelAsExtraDataInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}