<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 15.01.2019
 * Time: 16:14
 */

namespace app\models\excelReader\excelDataSave;


use app\models\excelReader\ExcelDataSaveInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class SaveToJsonFile implements ExcelDataSaveInterface
{

    protected $items=[];

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = \Yii::getAlias($filePath);
    }

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
     * @return bool
     */
    public function multipleSave(): bool
    {
        if ($this -> items){
            file_put_contents($this->filePath,json_encode($this->items));
        }
        return true;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        return false;
    }
}