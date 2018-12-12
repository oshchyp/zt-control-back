<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:20
 */

namespace app\models\asrelation;


use app\models\Culture;
use app\components\models\ModelAsRelationInterface;

class CulturesAsRelation extends Culture implements ModelAsRelationInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}