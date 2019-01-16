<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 15.01.2019
 * Time: 13:32
 */

namespace app\models\excelReader;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;

interface ExcelParserInterface
{

    /**
     * @return string
     */
    public function filePath() : string;

    /**
     * @return mixed
     */
    public function read();

    /**
     * @return Spreadsheet
     */
    public function sheet() : Spreadsheet;

    /**
     * @return RowIterator
     */
    public function rowIterator() : RowIterator;

    /**
     * @param ExcelDataConverterInterface $dataConverter
     * @return array
     */
    public function convertData(ExcelDataConverterInterface $dataConverter);

    /**
     * @param ExcelDataSaveInterface $excelDataSave
     * @return mixed
     */
    public function saveData(ExcelDataSaveInterface $excelDataSave);

}