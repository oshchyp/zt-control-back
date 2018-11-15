<?php

namespace app\controllers;

use app\models\excelparser\RTModel;
use app\models\excelparser\RTParser;
use app\models\filter\RTFilter;
use app\models\helper\Main;
use app\models\json_parser\Contracts;
use app\models\json_parser\Parser;
use app\models\RailwayTransit;
use app\models\ReflectionClass;
use app\models\RelationsInfo;
use app\models\xls\FirmsParser;
use app\models\xls\ParserActiveRecord;
use Yii;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;

class SiteController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'ips' => ['185.6.187.44']
                ]
            ],
            'except' => ['index', 'error'],
            'denyCallback' => function () {
                die('Access is denied');
            }
        ];
        return $behaviors;
    }



    public function actionError()
    {
      //  dump(Yii::getAlias('@app'),1);
      return 'werf';
    }


    public function actionDebug()
    {
        $instance = ReflectionClass::getInstance(RailwayTransit::className());
        $start = microtime(true);
        $instance->relationsId();
        $time = microtime(true) - $start;
        dump($time,1);
        die();
    }

    public function actionXls()
    {
        RTParser::parser([
            'activeSheet'=>2,
            'readerFilterOptions'=>[
                'rowMin' => 2,
                'rowMax'=>100
            ]
        ]);
        dump(RTModel::$counter);
        die();
    }

    public function actionReplaceName()
    {
        static::replaceName(\app\models\Stations::className());
    }

    private static function replaceName($modelName, $attribute = 'name')
    {
        foreach ($modelName::find()->all() as $instance) {
            $instance->$attribute = Main::stringToProperName($instance->$attribute);
            $instance->save();
        }
    }



}
