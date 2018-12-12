<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 30.10.2018
 * Time: 11:10
 */

namespace app\components\excelparser;


use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use yii\db\ActiveRecord;

interface ModelInterface
{

    /**
     * @param RowData $object
     * @param ExcelParser $excelParser
     * @param Row $row
     * @return ActiveRecord|$this
     */
    public static function instanceExcel(RowData $object, Row $row, ExcelParser $excelParser);

    /**
     * @param RowData $object
     * @param ExcelParser $excelParser
     * @param Row $row
     * @return mixed
     */
    public function runExcel(RowData $object, Row $row, ExcelParser $excelParser);

}