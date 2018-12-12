<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 15:04
 */

namespace app\components\models;


use yii\db\ActiveRecordInterface;

interface ModelAsRelationInterface extends ActiveRecordInterface
{

    /**
     * @return array
     */
    public static function relations();

}