<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 14:05
 */

namespace app\components\models;


use yii\db\ActiveRecordInterface;

interface ModelAsResourceInterface extends ActiveRecordInterface
{

    /**
     * @return array
     */
    public static function relations();

    /**
     * @return mixed
     */
    public function getRestValidators();

}