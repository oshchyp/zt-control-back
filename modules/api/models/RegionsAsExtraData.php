<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:37
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsExtraData;

class RegionsAsExtraData extends \app\models\Regions implements ModelAsExtraData
{

    public static function relations()
    {
        return [];
    }

}