<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.09.2018
 * Time: 10:49
 */

namespace app\models\xls;


use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\base\Model;

class ParserExcel extends Model
{

    public $file;

    public $model;

    public $rules;

    public $activeSheet=0;

    public $ignoreRows=[1];

    public $setOnlyValue = true;

    private $_excelObject;


    public function setActiveSheet($activeSheet = 0){
        $this->activeSheet = $activeSheet;
        if ($object = $this->getExcelObject()){
            $object->setActiveSheetIndex($this->activeSheet);
        }
        return $this;
    }

    public function getFile(){
        return \Yii::getAlias($this->file);
    }

    public function getRules(){
        $modelClass = $this->model;
        if (method_exists($modelClass,'excelRules') && !$this->rules){
            $this->rules = $modelClass::excelRules();
        }
        return $this->rules;
    }

    public function getModel(){
        return $this->model;
    }

    public function getExcelObject(){
        return $this->_excelObject;
    }

    public function existInIgnore($num){
        return in_array($num,$this->ignoreRows);
    }

    public function loadExcelObject(){
        if (is_file($this->getFile())) {
            $this->_excelObject = IOFactory::load($this->getFile());
        }
        return $this;
    }

    public function getCellsInfo($row){
        $result = [];
        foreach ($row->getCellIterator() as $k=>$cell){
           $this->assigningCellInfoToAttr($cell,$this->getRules(),$result);
        }
        return $result;
    }

    public function assigningCellInfoToAttr($cellObject,$rules,&$result){
        $column = $cellObject -> getColumn();
      //  dump($cellObject->getValue(),1);
        if (isset($rules[$column])){
            $attr = $rules[$column];
            $result[$attr] = $this->setOnlyValue ? $cellObject -> getFormattedValue() : $cellObject;
        }
    }

    public function parse($runValidate = true){
        $this->loadExcelObject();
        if ($runValidate && !$this->validate()){
            return false;
        }


        $activeSheet = $this->getExcelObject()-> getActiveSheet();
        $className = $this->model;
        foreach ($activeSheet->getRowIterator() as $k=>$row){
            if (!$this->existInIgnore($k)){
                $loadInfo = $this->getCellsInfo($row);
                $className::excelDataProcessing($loadInfo);
            }
        }
        return true;
    }

}