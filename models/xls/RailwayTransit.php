<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.09.2018
 * Time: 13:07
 */

namespace app\models\xls;


use app\models\Contracts;
use app\models\Culture;
use app\models\CustomerFirms;
use app\models\ExecutorFirms;
use app\models\ForwarderFirms;
use app\models\Stations;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use yii\base\Model;

class RailwayTransit extends Model implements ModelExcelInterface
{
    use ModelExcelTrait;

    public static $modelClassName;

    public static $allModelInstances;

    public static $auxInstances;

    public static function excelRules()
    {
        return [
            'A'=>'customerFirm',
            'B'=>'executorFirm',
            'C'=>'wagonNumber',
            'D'=>'consignmentNumber',
            'E'=>'weight',

            'G'=>'loadingWeight',
            'H'=>'datePlane',
            'I'=>'destinationStation',

            'K'=>'addInfo',
            'L'=>'product',
            'M'=>'forwarder',
            'N'=>'class',
            'O'=>'unloadingWeight',
            'P'=>'dateArrival',
            'Q'=>'price',

            'S'=>'tariff',
            'T'=>'contract',
        ];
    }



    public function loadModelInstance()
    {
        static::$modelClassName = \app\models\RailwayTransit::className();
        if ($instances = static::getAllModelInstances()){
            foreach ($instances as $instance){
                if ($instance->wagonNumber == $this->getLoadInfoValue('wagonNumber') && $instance->consignmentNumber == $this->getLoadInfoValue('consignmentNumber')){
                    $this->modelInstance=$instance;
                    return;
                }
            }
        }
        $this->modelInstance = new \app\models\RailwayTransit();
        return;
    }

    public function runObjectDataProcessing($data)
    {
        $model = $this->getModelInstance();
        $model->attributes = $this->getLoadInfoValues();
        $model->attributes = [
            'customerFirmUID' => $this->getInstanceByNameOrSave($this->getLoadInfoValue('customerFirm'),CustomerFirms::className())->uid,
            'executorFirmUID' => $this->getInstanceByNameOrSave($this->getLoadInfoValue('executorFirm'),ExecutorFirms::className())->uid,
            'forwarderFirmUID' => $this->getInstanceByNameOrSave($this->getLoadInfoValue('forwarder'),ForwarderFirms::className())->uid,
            'destinationStationUID' => $this->getInstanceByNameOrSave($this->getLoadInfoValue('destinationStation'),Stations::className())->uid,
            'contractUID' => $this->getInstanceByNameOrSave($this->getLoadInfoValue('contract'),Contracts::className())->uid,
            'productUID' => $this->getInstanceByNameOrSave($this->getProduct(),Culture::className())->uid,
            'datePlane' => $this->getDateTimestamp('datePlane'),
            'dateArrival' => $this->getDateArrivalTimestamp() ? $this->getDateArrivalTimestamp() : 0,
            'wagonNumber' => (int)$this->getLoadInfoValue('wagonNumber'),
            'price' => (float)$this->getLoadInfoValue('price'),
            'tariff' => (float)$this->getLoadInfoValue('tariff'),
            'consignmentNumber' => (int)$this->getLoadInfoValue('consignmentNumber'),
            'weightLoading' => (float)$this->getLoadInfoValue('weightLoading'),
        ];
        $model->setUid();

        $model->save();
       // dump($data,1);
//        dump($model->getAttributes());
//        dump($model->getErrors(),1);
    }

    public function getInstanceByNameOrSave($name,$class){
        $instance = static::getAuxInstanceByAttrValue($name,'name',$class);
        if ($instance->getIsNewRecord()){
            $instance->setUid();
            $instance->name = $name;
            $instance->save();
            static::addModelToAllAuxInstances($class,$instance);
        }
        return $instance;
    }

    public function cultureNameConverter($cultureName){

//        $convertArray = [
//            'кукуруза' => 'Кукуруза',
//            'ячмень' => 'Ячмень',
//            'пшеница' => 'Пшеница',
//            'ПОДСОЛ' => 'Подсолнух'
//        ];
        $convertArray = [
            'кукуруза' => 'кукурудза',
            'ячмень' => 'ячмінь',
            'пшеница' => 'пшениця',
            'ПОДСОЛ' => 'соняшник'
        ];

        return isset($convertArray[$cultureName]) ? $convertArray[$cultureName] : $cultureName;
    }

    public function getProduct(){
        return $this->cultureNameConverter($this->getLoadInfoValue('product'));
    }

    public function getDateArrivalTimestamp(){
        if ($result = $this->getDateTimestamp('dateArrival')) {
           return $result;
        }
        $value = $this->getLoadInfoValue('dateArrival');
        $value = str_replace(',','.',$value);
        if (count($explodeValue = explode('.',$value)) == 3 ) {
            if (iconv_strlen($year = array_pop($explodeValue)) !== 4) {
                $value = str_replace($year, substr($year, 0, 1) . '0' . substr($year, 1), $value);
            }
            return (new \DateTime($value)) -> getTimestamp();
        }
        return 0;

//        try {
//            return (new \DateTime($value)) -> getTimestamp();
//        } catch (\Exception $exception){
//            file_put_contents(\Yii::getAlias('@app/exec.log'),$this->getLoadInfoValue('dateArrival'));
//        }

    }

}