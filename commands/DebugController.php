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
use app\modules\api\models\FirmsFilter;
use yii\helpers\ArrayHelper;

class DebugController extends \yii\console\Controller
{

    public function actionIndex(){

         $firms = FirmsFilter::search(['stringForSearchAll' => "wef"]);
         dump($firms->createCommand()->getRawSql());
//         [$test1,$test2] = [1];
//         dump($test2);

    }

}