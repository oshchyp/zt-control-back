<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.09.2018
 * Time: 11:41
 */

namespace app\models\xls;


interface ModelExcelInterface
{

    public static function excelDataProcessing($loadInfo);

    public function runObjectDataProcessing();

}