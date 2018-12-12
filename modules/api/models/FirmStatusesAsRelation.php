<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.12.2018
 * Time: 15:29
 */

namespace app\modules\api\models;


use app\models\FirmStatuses;
use app\modules\api\models\interfaces\ModelAsRelation;

class FirmStatusesAsRelation extends FirmStatuses implements ModelAsRelation
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}