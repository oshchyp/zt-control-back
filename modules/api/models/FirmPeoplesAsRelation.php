<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 13:05
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsRelation;

class FirmPeoplesAsRelation extends \app\models\FirmPeoples implements ModelAsRelation
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}