<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 15.01.2019
 * Time: 15:08
 */

namespace app\models\excelReader;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;
use yii\base\Model;

class ExcelParser extends Model implements ExcelParserInterface
{

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var Spreadsheet
     */
    protected $sheet;

    /**
     * @var bool
     */
    protected $readDataOnly = false;

    /**
     * @var int
     */
    protected $activeSheet=0;

    /**
     * @param $filePath
     */
    public function setFilePath($filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function filePath(): string
    {
        return \Yii::getAlias($this->filePath);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function read()
    {
        $inputFileType = IOFactory::identify($this->filePath());
        /**
         * @var Xls $reader
         */
        $reader = IOFactory::createReader($inputFileType);
      //  $this->setReader($reader);
        $reader->setReadDataOnly($this->readDataOnly);
        $sheetNames = $reader->listWorksheetNames($this->filePath());
        if (!isset($sheetNames[$this->activeSheet])){
            throw new \Exception('No such sheet exists');
        }

        $reader->setLoadSheetsOnly($sheetNames[$this->activeSheet]);

//        $filterOptions = $this->getReaderFilterOptions();
//        if ($columns = $this->columns()){
//            $filterOptions['columns'] = $columns;
//        }
//
//        $reader->setReadFilter(new ReaderFilter($filterOptions));

        $this->sheet = $reader->load($this->filePath());
    }

    /**
     * @return Spreadsheet
     */
    public function sheet(): Spreadsheet
    {
        return $this->sheet;
    }

    /**
     * @return RowIterator
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function rowIterator(): RowIterator
    {
        return $this->sheet()->getActiveSheet()->getRowIterator();
    }

    /**
     * @param ExcelDataConverterInterface $dataConverter
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function convertData(ExcelDataConverterInterface $dataConverter)
    {
        foreach ($this->rowIterator() as $item){
            $dataConverter->loadItem($item);
        }
        return $dataConverter->items();
    }

    /**
     * @param ExcelDataSaveInterface $excelDataSave
     * @return mixed|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function saveData(ExcelDataSaveInterface $excelDataSave)
    {
        foreach ($this->rowIterator() as $item){
            $excelDataSave->loadItem($item);
            $excelDataSave->save();
        }
        $excelDataSave->multipleSave();
    }

}