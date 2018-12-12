<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.12.2018
 * Time: 15:30
 */

namespace app\models\asextradata;


use app\models\FirmStatuses;
use app\components\models\ModelAsExtraDataInterface;

class FirmStatusesAsExtraData extends FirmStatuses implements ModelAsExtraDataInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}