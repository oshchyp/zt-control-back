<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.08.2018
 * Time: 10:43
 */

namespace app\models\xls;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\base\Model;
use yii\caching\DummyCache;
use yii\helpers\VarDumper;

class Parser extends Model
{
   public $filePath;

   public $documentObject;

   public $activeSheet=0;

   public $ignoreRows = [];

   public $column = [];

   public $model;

    public static function getInstance($params = [])
    {
        $instance = new static();
        if ($params) {
            foreach ($params as $attr => $value) {
                $instance->$attr = $value;
            }
        }
        return $instance;
    }

   public function loadDocumentObject(){
       if (is_file(Yii::getAlias($this->filePath))) {
           $this->documentObject = IOFactory::load(Yii::getAlias($this->filePath));
       }
       if ($this->documentObject){
           $this->documentObject->setActiveSheetIndex($this->activeSheet);
       }
       return $this;
   }

   public function getCells($row){
        $result = [];
        foreach ($row->getCellIterator() as $k=>$cell){
            $column = $cell -> getColumn();
            if (isset($this->column[$column])){
                $attr = $this->column[$column];
                $result[$attr] = $cell;
            }
        }
        return $result;
   }

   public function parse(){
       $activeSheet = $this->documentObject ? $this->documentObject -> getActiveSheet() : null;
       if ($activeSheet && $rowIterator = $activeSheet->getRowIterator()){
           $className = $this->model;
           foreach ($rowIterator as $key=>$row){
               if (!in_array($key,$this->ignoreRows)){
                   $cellsInfo = $this->getCells($row);
                   $object = $className::getInstance($cellsInfo);
                   $object->attributes = $cellsInfo;
                   $object->loadModel($this);
               }
           }
       }
       return $this;
   }

}