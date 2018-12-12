<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 13:48
 */

namespace app\models\asextradata;


use app\components\models\ModelAsExtraDataInterface;

class FirmManagersAsExtraData extends \app\models\FirmManagers implements ModelAsExtraDataInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}