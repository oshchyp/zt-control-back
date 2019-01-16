<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 15.01.2019
 * Time: 13:12
 */

namespace app\models\excelReader;


use PhpOffice\PhpSpreadsheet\Worksheet\Row;

interface ExcelDataConverterInterface
{

    /**
     * @param Row $row
     * @return mixed
     */
    public function loadItem(Row $row);

    /**
     * @return array
     */
    public function items(): array;


}