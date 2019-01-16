<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 15.01.2019
 * Time: 15:55
 */

namespace app\models\excelReader\excelDataConverter;


use app\models\excelReader\ExcelDataConverterInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class ArrayConverter implements ExcelDataConverterInterface
{

    public $items=[];

    /**
     * @param Row $row
     * @return mixed|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function loadItem(Row $row)
    {
        $item = [];
        foreach ($row->getCellIterator() as $cell){
            $item[$cell->getColumn()] = $cell->getCalculatedValue();
        }
        $this->items[] = $item;
    }

    /**
     * @return array
     */
    public function items(): array
    {
        return $this->items;
    }
}