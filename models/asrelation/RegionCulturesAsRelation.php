<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.12.2018
 * Time: 12:07
 */

namespace app\models\asrelation;


use app\components\models\ModelAsRelationInterface;

class RegionCulturesAsRelation extends \app\models\RegionCultures implements ModelAsRelationInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}