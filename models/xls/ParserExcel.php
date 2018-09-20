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

    public $fromRow;

    public $toRow;

    public $setOnlyValue;

    private $_excelObject;

    private $_activeSheet;

    /**
     * @param array $config
     * @return mixed
     */
    public static function getInstance(array $config = []){
        return new static($config);
    }

    /**
     * @param int $activeSheet
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function setActiveSheet($activeSheet = 0){
        $this->activeSheet = $activeSheet;
        $this->_loadActiveSheetObject();
        return $this;
    }

    /**
     * @return bool|string
     */
    public function getFile(){
        return \Yii::getAlias($this->file);
    }

    /**
     * @return mixed
     */
    public function getRules(){
        $modelClass = $this->model;
        if (method_exists($modelClass,'excelRules') && !$this->rules){
            $this->rules = $modelClass::excelRules();
        }
        return $this->rules;
    }

    /**
     * @return mixed
     */
    public function getModel(){
        return $this->model;
    }

    /**
     * @return null
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */

    public function getExcelObject(){
        if ($this->_excelObject === null){
            $this->_loadExcelObject();
        }
        return $this->_excelObject;
    }

    /**
     * @return null
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function getActiveSheet(){
        if ($this->_activeSheet === null){
            $this->_loadActiveSheetObject();
        }
        return $this->_activeSheet;
    }

    /**
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function _loadExcelObject(){
        if (is_file($this->getFile())){
            $this->_excelObject = IOFactory::load($this->getFile());
         }
        return $this;
    }

    /**
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function _loadActiveSheetObject(){
        if ($this->getExcelObject()){
            $this->getExcelObject()->setActiveSheetIndex($this->activeSheet);
            $this->_activeSheet = $this->getExcelObject()-> getActiveSheet();
        }
        return $this;
    }

    /**
     * @param $num
     * @return bool
     */
    public function existInIgnore($num){
        if (in_array($num,$this->ignoreRows) || ($this->fromRow && $num < $this->fromRow) || ($this->toRow && $num > $this->toRow)){
            return true;
        }

        return false;
    }

    /**
     * @param $row
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function getCellsInfo($row){
        $result = [];
        foreach ($this->getRules() as $lit=>$attr){
            $result[$attr] = $this->getActiveSheet()->getCell($lit.$row);
        }
        return $result;
    }

    /**
     * @param bool $runValidate
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function parse($runValidate = true){
        if ($runValidate && !$this->validate()){
            return false;
        }
        $className = $this->model;
        $from = $this->fromRow ? $this->fromRow : 1;
        $to = $this->toRow ? $this->toRow : $this->getActiveSheet()->getHighestRow();
        for ($k=$from; $k <=$to;  $k++) {
            if (!$this->existInIgnore($k)){
                $loadInfo = $this->getCellsInfo($k);
                $instance = $className::excelDataProcessingInstance($loadInfo);
                $instance->setLoadInfo($loadInfo);
                $instance->loadModelInstance($loadInfo);
                $instance -> runObjectDataProcessing($k);

            }
        }
        return true;
    }

}