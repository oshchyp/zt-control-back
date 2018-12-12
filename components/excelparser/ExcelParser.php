<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 30.10.2018
 * Time: 11:01
 */

namespace app\components\excelparser;


use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;
use yii\base\Model;

class ExcelParser extends Model
{

    /**
     * @var string
     */
    public $filePath;

    /**
     * @var ModelInterface
     */
    public $modelClass;


    /**
     * @var int
     */
    public $activeSheet=0;

    /**
     * @var array
     */
    public $rowRule = [];

    /**
     * @var array
     */
    private $_rowRuleAssociative;

    /**
     * @var array
     */
    public $readerFilterOptions = [];

    /**
     * @var bool
     */
    public $readDataOnly = true;

    /**
     * @var RowData[]
     */
    public $content = [];

    /**
     * @var Spreadsheet
     */
    private $_spreedSheet;

    /**
     * @var BaseReader
     */
    private $_reader;

    /**
     * @var ModelInterface
     */
    private $_modelInstance;

    private $_rowNow;

    /**
     * @return string
     */
    public function getFilePath()
    {
        return \Yii::getAlias($this->filePath);
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return ModelInterface
     * @throws \Exception
     */
    public function getModelClass()
    {
        if ($this->_modelInstance === null && $this->modelClass !==null){
            $className = $this->modelClass;
            $this->_modelInstance = new $className();
            if (!$this->_modelInstance instanceof ModelInterface){
                throw new \Exception('class model must implement the interface app\models\excelparser\ModelInterface');
            }
        }
        return $this->modelClass;
    }

    /**
     * @param ModelInterface $modelClass
     */
    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @return int
     */
    public function getActiveSheet()
    {
        return $this->activeSheet;
    }

    /**
     * @param int $activeSheet
     */
    public function setActiveSheet($activeSheet)
    {
        $this->activeSheet = $activeSheet;
    }

    /**
     * @return Spreadsheet
     */
    public function getSpreedSheet()
    {
        return $this->_spreedSheet;
    }

    /**
     * @param Spreadsheet $spreedSheet
     */
    public function setSpreedSheet(Spreadsheet $spreedSheet)
    {
        $this->_spreedSheet = $spreedSheet;
    }

    /**
     * @return array
     */
    public function getRowRule()
    {
        return $this->rowRule;
    }

    public function columns(){
        $result = [];
        foreach ($this->getRowRule() as $item){
            $result[]=$item[0];
        }
        return $result;
    }

    /**
     * @param array $rowRule
     */
    public function setRowRule($rowRule)
    {
        $this->rowRule = $rowRule;
    }

    /**
     * @return array
     */
    public function getReaderFilterOptions()
    {
        return $this->readerFilterOptions;
    }

    /**
     * @param $readerFilterOptions
     */
    public function setReaderFilterOptions($readerFilterOptions)
    {
        $this->readerFilterOptions = $readerFilterOptions;
    }

    /**
     * @return bool
     */
    public function isReadDataOnly()
    {
        return $this->readDataOnly;
    }

    /**
     * @param bool $readDataOnly
     */
    public function setReadDataOnly($readDataOnly)
    {
        $this->readDataOnly = $readDataOnly;
    }

    /**
     * @return array
     */
    public function getRowRuleAssociative()
    {
        if ($this->_rowRuleAssociative === null && $this->getRowRule()){
            $this->_rowRuleAssociative = [];
             foreach ($this->getRowRule() as $item){
                 $this->_rowRuleAssociative[$item[0]] = $item;
             }
        }
        return $this->_rowRuleAssociative;
    }

    /**
     * @param array $rowRuleAssociative
     */
    public function setRowRuleAssociative($rowRuleAssociative)
    {
        $this->_rowRuleAssociative = $rowRuleAssociative;
    }

    /**
     * @return RowData[]
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param RowData[] $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getRowNow()
    {
        return $this->_rowNow;
    }

    /**
     * @param mixed $rowNow
     */
    public function setRowNow($rowNow)
    {
        $this->_rowNow = $rowNow;
    }

    /**
     * @return BaseReader
     */
    public function getReader()
    {
        return $this->_reader;
    }

    /**
     * @param BaseReader $reader
     */
    public function setReader($reader)
    {
        $this->_reader = $reader;
    }


    /**
     * @return bool|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadSpreadSheet(){

        $inputFileType = IOFactory::identify($this->getFilePath());
        /**
         * @var Xls $reader
         */
        $reader = IOFactory::createReader($inputFileType);
        $this->setReader($reader);
        $reader->setReadDataOnly($this->readDataOnly);
        $sheetNames = $reader->listWorksheetNames($this->getFilePath());
        if (!isset($sheetNames[$this->getActiveSheet()])){
           throw new \Exception('No such sheet exists');
        }

        $reader->setLoadSheetsOnly($sheetNames[$this->getActiveSheet()]);

        $filterOptions = $this->getReaderFilterOptions();
        if ($columns = $this->columns()){
            $filterOptions['columns'] = $columns;
        }

        $reader->setReadFilter(new ReaderFilter($filterOptions));

        $spreadSheet = $reader->load($this->getFilePath());
        $this->setSpreedSheet($spreadSheet);
    }

    /**
     * @param $cellInfo
     * @return mixed
     */
    public function cellValue($cellInfo){
        $index = $cellInfo->getColumn();
        if (key_exists($index,$this->getRowRuleAssociative())){
            $info = $this->getRowRuleAssociative()[$index];
            if (isset($info[2])){
                return call_user_func($info[2],$cellInfo);
            }
        }
        return $cellInfo->getCalculatedValue();
    }

    /**
     * @param $cellInfo
     * @return mixed
     */
    public function cellIndex($cellInfo){
        $index = $cellInfo->getColumn();
        if (key_exists($index,$this->getRowRuleAssociative())){
            $index = $this->getRowRuleAssociative()[$index][1];
        }
        return $index;
    }

    public function startRow(){
        if (isset($this->readerFilterOptions['rowMin'])){
            return $this->readerFilterOptions['rowMin'];
        }
        return 1;
    }

    /**
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function run(){
        $this->loadSpreadSheet();
        $modelClass = $this->getModelClass();

        $activeSheet =  $this->getSpreedSheet()->getActiveSheet();
        $rowIterator = $activeSheet->getRowIterator($this->startRow());

        foreach ($rowIterator as $index => $iterator){
            $dataConfig = [];
            $ceilIterator = $iterator->getCellIterator();
           // $this->set$iterator->getRowIndex();
            foreach ($ceilIterator as $cellInfo){
                $dataConfig[$this->cellIndex($cellInfo)] = $this->cellValue($cellInfo);
            }

            $dataObject = RowData::createObject($dataConfig);
            if ($modelClass){
                $instance = $modelClass::instanceExcel($dataObject,$iterator,$this);
                if ($instance && is_object($instance) && $instance instanceof ModelInterface) {
                    $instance->attributes = $dataConfig;
                    $instance->runExcel($dataObject, $iterator, $this);
                }
            }
            $this->content[$index] = $dataObject;
        }
        return $this;
    }

    /**
     * @param array $config name-value
     * @return ExcelParser
     */
    public static function parser($config=[]){
        $object = new static($config);
        $object->run();
        return $object;
    }




}