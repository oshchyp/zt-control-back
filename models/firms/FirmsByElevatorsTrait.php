<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.01.2019
 * Time: 09:42
 */

namespace app\models\firms;


use app\components\models\EstablishRelation;
use app\models\asrelation\ZlataElevators;

trait FirmsByElevatorsTrait
{

    public static $aliasTableName='`firms`.';

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElevator(){
        return EstablishRelation::hasOne($this,new ZlataElevators(),['bit'=>'elevatorBit']);
    }

    /**
     * @return mixed
     */
    public static function find()
    {
        $elevatorBit = \Yii::$app->user->identity ? \Yii::$app->user->identity->elevatorBit : 1;
        return parent::find()->where($elevatorBit.' & '.static::$aliasTableName.'`elevatorBit`');
    }

}