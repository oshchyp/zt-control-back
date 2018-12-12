<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 30.10.2018
 * Time: 14:57
 */

namespace app\components\excelparser;


use app\models\ActiveRecord;
use app\models\Contracts;
use app\models\Culture;
use app\models\RailwayTransit;
use app\models\RTFirms;
use app\models\Stations;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use yii\helpers\ArrayHelper;

/**
 * Class RTModel
 * @package app\models\excelparser
 */
class RTModel extends RailwayTransit implements ModelInterface
{


    public static $counter = 0;

    private static $_models;

    private static $_relationModels;

    public function beforeValidate()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public static function getModels()
    {
        if (self::$_models===null) {
            $models = static::find()->all();
            foreach ($models as $item) {
                self::$_models[static::modelIdentityKey($item)] = $item;
            }
        }

        return self::$_models;
    }

    public static function modelIdentityKey(RailwayTransit $object)
    {
        return $object->wagonNumber . '_' . $object->consignmentNumber;
    }

    /**
     * @param $dataObject
     * @return RTModel
     */
    public static function getModel($dataObject){
        if (key_exists(self::modelIdentityKey($dataObject),self::getModels())){
            return self::getModels()[self::modelIdentityKey($dataObject)];
        }
        return new static();
    }

    /**
     * @param mixed $models
     */
    public static function setModels($models)
    {
        self::$_models = $models;
    }

    /**
     * @param ActiveRecord $modelClass
     * @return mixed
     */
    public static function getRelationModels($modelClass)
    {
        if (self::$_relationModels === null || !key_exists($modelClass,self::$_relationModels)){
            self::$_relationModels[$modelClass] = [];
            foreach ($modelClass::find()->all() as $item){
                self::$_relationModels[$modelClass][mb_strtolower($item->name)] = $item;
            }
        }
        return self::$_relationModels[$modelClass];
    }

    /**
     * @param $modelClass
     * @param $name
     * @return null
     */
    public static function getRelationModel($modelClass,$name){
        if (!key_exists(mb_strtolower($name),self::getRelationModels($modelClass))){
            $instance = new $modelClass();
            $instance->name = $name;
            if ($instance instanceof ActiveRecord){
                $instance->setUid();
            }
            if ($instance->save()){
                self::$_relationModels[$modelClass][mb_strtolower($name)] = $instance;
            } else {
                return null;
            }
        }
        return self::getRelationModels($modelClass)[mb_strtolower($name)];
    }

    /**
     * @param mixed $relationModels
     */
    public static function setRelationModels($relationModels)
    {
        self::$_relationModels = $relationModels;
    }

    /**
     * @param RowData $rowData
     */
    public function setRelationsUid(RowData $rowData){
        $relations = [
            ['customerFirm','customerFirmUID', RTFirms::className()],
            ['executorFirm', 'executorFirmUID', RTFirms::className()],
            ['destinationStation', 'destinationStationUID', Stations::className()],
            ['product','productUID', Culture::className()],
            ['forwarderFirm', 'forwarderFirmUID', RTFirms::className()],
            ['contract', 'contractUID', Contracts::className()],
        ];
        foreach ($relations as $item){
            $attributeName = $item[0];
            $attributeUID = $item[1];
            if ($rowData->$attributeName && $instance = self::getRelationModel($item[2], $rowData->$attributeName)) {
               $this->$attributeUID = $instance->uid;
            }
        }
    }

    public function setClassID($object){
        if ($object->class){
            foreach (RailwayTransit::classes() as $item){
               if ($item['name'] == str_replace([' ','.'],'',$object->class)){
                   $this->classID = $item['id'];
                   return;
               }
            }
        }
    }

    public static function tableName()
    {
        return 'railwayTransit';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), []);
    }


    /**
     * @param RowData $object
     * @param ExcelParser $excelParser
     * @param Row $row
     * @return mixed
     */
    public function runExcel(RowData $object, Row $row, ExcelParser $excelParser)
    {
        self::$counter++;
        $this->setRelationsUid($object);
        $this->setClassID($object);
        $this->statusID = 1;
        if (!$this->save()){
            dump($this->price);
            dump($this->getErrors(),1);
        }

   }

    /**
     * @param RowData $dataObject
     * @param ExcelParser $excelParser
     * @param Row $row
     * @return \yii\db\ActiveRecord|$this
     */
    public static function instanceExcel(RowData $dataObject, Row $row, ExcelParser $excelParser)
    {
        $RTModel = new RailwayTransit();
        $RTModel->attributes = $dataObject->attributes();
        if (self::getModels() && key_exists(self::modelIdentityKey($RTModel),self::getModels())){
          return null;
        }
        return new static();
    }

}