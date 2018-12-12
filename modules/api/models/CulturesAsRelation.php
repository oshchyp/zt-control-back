<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:20
 */

namespace app\modules\api\models;


use app\models\Culture;
use app\modules\api\models\interfaces\ModelAsRelation;

class CulturesAsRelation extends Culture implements ModelAsRelation
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}