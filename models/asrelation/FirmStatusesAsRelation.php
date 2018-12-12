<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.12.2018
 * Time: 15:29
 */

namespace app\models\asrelation;


use app\models\FirmStatuses;
use app\components\models\ModelAsRelationInterface;

class FirmStatusesAsRelation extends FirmStatuses implements ModelAsRelationInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}