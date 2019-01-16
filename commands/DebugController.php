<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.10.2018
 * Time: 11:26
 */

namespace app\commands;



use app\models\excelReader\excelDataConverter\ArrayConverter;
use app\models\excelReader\excelDataSave\SaveToJsonFile;
use app\models\excelReader\ExcelParser;
use app\models\firms\FirmsByElevatorsAsSave;

class DebugController extends \yii\console\Controller
{

    /**
     *
     */
    public function actionIndex(){
       $parser = new ExcelParser();
       $parser->setFilePath('files/xls/hmelnik_firms.xlsx');
       $parser -> read();
       $parser->saveData(new SaveToJsonFile('files/json/hmelnik_firms.json'));
     }

}