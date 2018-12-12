<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:42
 */

namespace app\modules\api\models;


use app\models\Culture;
use app\modules\api\models\interfaces\ModelAsExtraData;

class CultureAsExtraData extends Culture implements ModelAsExtraData
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}