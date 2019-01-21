<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 15:07
 */

namespace app\components\models;


use yii\db\ActiveQuery;
use yii\db\ActiveRecordInterface;
use yii\db\Query;

interface ModelAsExtraDataInterface extends ActiveRecordInterface
{





    /**
     * @return array
     */
    public static function relations();
}