<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.12.2018
 * Time: 15:30
 */

namespace app\modules\api\models;


use app\models\FirmStatuses;
use app\modules\api\models\interfaces\ModelAsExtraData;

class FirmStatusesAsExtraData extends FirmStatuses implements ModelAsExtraData
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}