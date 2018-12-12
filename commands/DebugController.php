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
use app\modules\api\models\Farms;
use app\modules\api\models\FarmsToSave;
use app\modules\api\models\FirmOwners;
use app\modules\api\models\FirmsFilter;
use yii\helpers\ArrayHelper;

class DebugController extends \yii\console\Controller
{

    public function actionIndex(){
//        $data = [
//            'name' => 'trtrtr',
//            'regionUID' => 'bY3TtY8B-TiNi-DR69-QzdE-YDiFezYkAeit',
//            'pointUID' => 'T4339dff-Qe98-8ySA-y2A5-2yzYkQDZB64i',
//            'firmUID' => 'yksYzkfh-2td7-88r4-EbT5-kYBB78GHDRzt',
//            'cultures' => [
//                [
//                    'cultureUID' => '48f9e143-f6ca-11e7-aa53-6466b304e311',
//                    'square' => 234,
//                    'yield' => 33,
//                ],
//                [
//                    'cultureUID' => '3314c918-ebc0-11e7-8660-60a44cafafcb',
//                    'square' => 333,
//                    'yield' => 23,
//                ]
//            ]
//        ];
//echo json_encode($data); die();
//        $model = new FarmsToSave();
//        $model->load($data,'');
//        $model->save();
//        dump(ArrayHelper::toArray(Farms::find()->all()));


        $firms = \app\modules\api\models\Firms::find()->with(\app\modules\api\models\Firms::relations())->addOrderBy(['firms.id' => SORT_DESC])->limit(10)->all();

        dump(ArrayHelper::toArray($firms,[\app\modules\api\models\Firms::className()=>['cultures']])[0]);
    }

}