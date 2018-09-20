<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 13.09.2018
 * Time: 11:14
 */

namespace app\models\xls;


use app\models\Contracts;
use yii\base\Model;

class RailwayTransitContracts extends Model implements ModelExcelInterface
{

    use ModelExcelTrait;

    public static $modelClassName;

    public static $allModelInstances;

    public static $allModelInstancesConvert;

    public static $auxInstances;


    public static function excelRules()
    {
        return [
            'C'=>'wagonNumber',
            'D'=>'consignmentNumber',
            'T'=>'contract',
            'N' => 'class',
        ];
    }


    public static function getAllModelInstancesConvert(){
        if (static::$allModelInstancesConvert===null){
            static::$allModelInstancesConvert = [];
            if ($instances = static::getAllModelInstances()){
                foreach ($instances as $instance){
                    static::$allModelInstancesConvert[$instance->wagonNumber][$instance->consignmentNumber] = $instance;
                }
            }
        }
        return static::$allModelInstancesConvert;
    }

    public function loadModelInstance()
    {
        static::$modelClassName = \app\models\RailwayTransit::className();
        if ($instances = static::getAllModelInstancesConvert()){

            if (isset($instances[$this->getLoadInfoValue('wagonNumber')][$this->getLoadInfoValue('consignmentNumber')])) {
                $this->modelInstance = $instances[$this->getLoadInfoValue('wagonNumber')][$this->getLoadInfoValue('consignmentNumber')];
                return;
            }
        }
        $this->modelInstance = new \app\models\RailwayTransit();
        return;
    }

    public function runObjectDataProcessing($data)
    {
        $model = $this->getModelInstance();
        if (!$model->getIsNewRecord()){
            $model->contractUID = static::getAuxInstanceByAttrValueOrSave($this->getLoadInfoValue('contract'),'name',Contracts::className())->uid;
            $model->class = $this->getLoadInfoValue('class');
            $model->save();
        }

    }
}