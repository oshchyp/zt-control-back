<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:37
 */

namespace app\models\asextradata;


use app\components\models\ModelAsExtraDataInterface;

class RegionsAsExtraData extends \app\models\Regions implements ModelAsExtraDataInterface
{

    public static function relations()
    {
        return [];
    }

}