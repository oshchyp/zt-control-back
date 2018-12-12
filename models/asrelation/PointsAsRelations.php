<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:14
 */

namespace app\models\asrelation;


use app\models\Points;
use app\components\models\ModelAsRelationInterface;

class PointsAsRelations extends Points implements ModelAsRelationInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}