<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.10.2018
 * Time: 11:26
 */

namespace app\commands;


use app\models\DateSet;
use app\models\excelparser\RegionCulturesParser;
use app\models\FirmCultures;
use app\models\Firms;
use app\models\RailwayTransit;
use app\models\RailwayTransitMultiSave;
use app\models\Regions;
use app\models\SMSApi;
use app\models\xls\ParserExcel;
use app\models\xls\RegionCultures;
use yii\helpers\ArrayHelper;

class DebugController extends \yii\console\Controller
{

    public function actionIndex(){
        $inst = RegionCulturesParser::parser(['activeSheet'=>1,'readerFilterOptions' => ['rowMin'=>2]]);
//
//        $arr = [];
//
//        foreach (Regions::find()->all() as $item){
//            $arr[$item->name] = $item->uid;
//        }
//
//        var_export($arr);

   }

}