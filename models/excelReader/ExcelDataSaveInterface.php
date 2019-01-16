<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 15.01.2019
 * Time: 13:30
 */

namespace app\models\excelReader;


use PhpOffice\PhpSpreadsheet\Worksheet\Row;

interface ExcelDataSaveInterface
{

    /**
     * @param Row $row
     * @return mixed
     */
    public function loadItem(Row $row);

    /**
     * @return bool
     */
    public function multipleSave(): bool;

    /**
     * @return bool
     */
    public function save(): bool;

}