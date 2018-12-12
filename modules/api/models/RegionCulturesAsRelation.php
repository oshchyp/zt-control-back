<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.12.2018
 * Time: 12:07
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsRelation;

class RegionCulturesAsRelation extends \app\models\RegionCultures implements ModelAsRelation
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}