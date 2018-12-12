<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:14
 */

namespace app\modules\api\models;


use app\models\Points;
use app\modules\api\models\interfaces\ModelAsRelation;

class PointsAsRelations extends Points implements ModelAsRelation
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}