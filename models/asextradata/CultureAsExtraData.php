<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:42
 */

namespace app\models\asextradata;


use app\models\Culture;
use app\components\models\ModelAsExtraDataInterface;

class CultureAsExtraData extends Culture implements ModelAsExtraDataInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}