<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:35
 */

namespace app\models\asextradata;


use app\components\models\ModelAsExtraDataInterface;

class FirmsAsExtraData extends \app\models\Firms implements ModelAsExtraDataInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}