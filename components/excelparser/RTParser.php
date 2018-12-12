<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 30.10.2018
 * Time: 15:06
 */

namespace app\components\excelparser;


use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RTParser extends ExcelParser
{

    public $modelClass = '\app\models\excelparser\RTModel';

    public $filePath = '@app/files/xls/file.xlsx';

    public function getRowRule()
    {
        return [
            ['A', 'customerFirm', [$this, 'trim']],
            ['B', 'executorFirm', [$this, 'trim']],
            ['C', 'wagonNumber', [$this, 'trim']],
            ['D', 'consignmentNumber', [$this, 'trim']],
            ['E', 'weight', [$this, 'numberValue']],

            ['G', 'loadingWeight', [$this, 'numberValue']],
            ['H', 'datePlane', [$this, 'dataValue']],
            ['I', 'destinationStation', [$this, 'trim']],

            ['K', 'addInfo', [$this, 'trim']],
            ['L', 'product', [$this, 'trim']],
            ['M', 'forwarderFirm', [$this, 'trim']],
            ['N', 'class', [$this, 'trim']],
            ['O', 'unloadingWeight', [$this, 'trim']],
            ['P', 'dateArrival', [$this, 'dataValue']],
            ['Q', 'price', [$this, 'numberValue']],

            ['S', 'tariff', [$this, 'trim']],
            ['T', 'contract', [$this, 'trim']],
        ];

    }

    /**
     * @param Cell $cellInfo
     * @return int
     * @throws \Exception
     */
    public function dataValue($cellInfo)
    {

        $value = $cellInfo->getValue();
        $exceptionMessage = 'not valid data ' . $value . ' in ' . $cellInfo->getColumn() . $cellInfo->getRow();

        if ($cellInfo->getDataType() == 'n') {
            return Date::excelToTimestamp($cellInfo->getValue());
        } else if ($cellInfo->getDataType() == 's') {

            if (count(explode(',', $value)) == 3) {
                $expl = explode(',', $value);
                $format = 'd,m,Y';
            } else if (count(explode('.', $value)) == 3) {
                $expl = explode('.', $value);
                $format = 'd.m.Y';
            } else if (count(explode(',', $value)) == 4){
                $ex = explode(',', $value);
                $expl = [$ex[0],$ex[1],$ex[3]];
                $format = 'd,m,,Y';
            } else if ($value == 'Возврат'){
                return null;
            } else {
                throw new \Exception($exceptionMessage);
            }

            if (iconv_strlen($expl[2]) == 4) {
                return \DateTime::createFromFormat($format, $value)->getTimestamp();
            } else if (iconv_strlen($expl[2]) == 3) {
                $value = str_replace($expl[2], str_replace('0', '01', $expl[2]), $value);
                return \DateTime::createFromFormat($format, $value)->getTimestamp();
            }

        } else if (!$value) {
            return null;
        }

        throw new \Exception($exceptionMessage);

    }

    /**
     * @param Cell $cellInfo
     * @return int
     * @throws \Exception
     */
    public function trim($cellInfo){
        $value = $cellInfo->getCalculatedValue();
        return is_string($value) ? trim($value) : $value;
    }

    /**
     * @param Cell $cellInfo
     * @return int
     * @throws \Exception
     */
    public function numberValue($cellInfo){
        return (float)$cellInfo->getCalculatedValue();
    }


}