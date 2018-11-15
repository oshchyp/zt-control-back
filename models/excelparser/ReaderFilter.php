<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 30.10.2018
 * Time: 12:33
 */

namespace app\models\excelparser;


use yii\base\Model;

class ReaderFilter extends Model implements  \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
{


    public $rowMax;

    public $rowMin;

    public $columns;
    /**
     * Should this cell be read?
     *
     * @param string $column Column address (as a string value like "A", or "IV")
     * @param int $row Row number
     * @param string $worksheetName Optional worksheet name
     *
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '')
    {

        $failureConditions = [
            ($this->rowMin !==null && $row < $this->rowMin),
            ($this->rowMax !==null && $row > $this->rowMax),
            ($this->columns !==null && !in_array($column,$this->columns))
        ];

        foreach ($failureConditions as $condition){
            if ($condition) return false;
//            if ($row == 1){
//                dump($this -> rowMax,1);
//            }
        }
     //   dump($this -> rowMin,1);
        return true;
    }
}