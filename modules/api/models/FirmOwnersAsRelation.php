<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 16:04
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsRelation;

class FirmOwnersAsRelation extends \app\models\FirmOwners implements ModelAsRelation
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}