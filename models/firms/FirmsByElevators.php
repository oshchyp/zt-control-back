<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.01.2019
 * Time: 16:23
 */

namespace app\models\firms;


use app\components\bitAccess\BitAccessFilter;
use app\components\models\EstablishRelation;
use app\models\asrelation\ZlataElevators;

class FirmsByElevators extends Firms
{

    /**
     * @return array
     */
    public function fields()
    {
        return array_merge(parent::fields(),['elevator']);
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return array_merge(parent::relations(),['elevator']);
    }

    /**
     * @return mixed
     */
    public function getElevator(){
        return EstablishRelation::hasOne($this,new ZlataElevators(),['bit'=>'elevatorBit']);
    }

    /**
     * @return mixed
     */
    public static function find()
    {
        $query = parent::find();
        BitAccessFilter::getInstance($query,'elevatorBit',\Yii::$app->user->identity ? \Yii::$app->user->identity->elevatorViewBit : null)->filter();
        return $query;
    }

}