<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:35
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsExtraData;

class FirmsAsExtraData extends \app\models\Firms implements ModelAsExtraData
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}