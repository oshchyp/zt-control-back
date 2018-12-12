<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:09
 */

namespace app\models\asrelation;


use app\components\models\ModelAsRelationInterface;

class RegionsAsRelation extends \app\models\Regions implements ModelAsRelationInterface
{
    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }

}