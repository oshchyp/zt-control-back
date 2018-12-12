<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 13:03
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsExtraData;

class FirmPeoplesAsExtraData extends \app\models\FirmPeoples implements ModelAsExtraData
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}