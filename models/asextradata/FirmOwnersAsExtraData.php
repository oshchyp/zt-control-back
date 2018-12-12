<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 16:26
 */

namespace app\models\asextradata;

use app\components\models\ModelAsExtraDataInterface;

class FirmOwnersAsExtraData extends \app\models\FirmOwners implements ModelAsExtraDataInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}