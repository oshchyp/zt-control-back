<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:38
 */

namespace app\modules\api\models;


use app\models\Points;
use app\modules\api\models\interfaces\ModelAsExtraData;

class PointsAsExtraData extends Points implements ModelAsExtraData
{

    /**
     * @return array
     */
    public static function relations()
    {
       return [];
    }
}