<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:09
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsRelation;

class RegionsAsRelation extends \app\models\Regions implements ModelAsRelation
{
    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }

}