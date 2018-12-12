<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 13:51
 */

namespace app\models\asrelation;


use app\components\models\ModelAsRelationInterface;
use app\models\FirmManagers;

class FirmManagersAsRelation extends FirmManagers implements ModelAsRelationInterface
{
    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}