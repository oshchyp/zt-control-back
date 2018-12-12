<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 16:04
 */

namespace app\models\asrelation;

use app\components\models\ModelAsRelationInterface;
use app\models\FirmOwners;

class FirmOwnersAsRelation extends  FirmOwners implements ModelAsRelationInterface
{
    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}