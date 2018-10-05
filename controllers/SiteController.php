<?php

namespace app\controllers;

use app\models\CustomerFirms;
use app\models\db_data_processing\ExecutorDelete;
use app\models\db_data_processing\RTFirms;
use app\models\filter\RailwayTransitFilter;
use app\models\Firms;
use app\models\helper\Main;
use app\models\json_parser\Contracts;
use app\models\json_parser\Parser;
use app\models\RailwayTransit;
use app\models\xls\FirmsParser;
use app\models\xls\ParserActiveRecord;
use app\models\xls\ParserExcel;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\Response;
use yii\web\Controller;

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
                    'ips' => ['213.231.31.66']
                ]
            ],
            'except' => ['index', 'error'],
            'denyCallback' => function () {
                die('Access is denied');
            }
        ];
        return $behaviors;
    }

    /*
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionError()
    {
        return Yii::$app->response->redirect('/');
    }



    public function actionParseRailwayTransit(){

    }

    public function actionParseRailwayContracts(){

    }

    public function actionXls()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);

        $config = [
            'file' => '@app/files/xls/railway.xls',
            'model' => \app\models\xls\RailwayTransit::className(),
            'activeSheet' => 2,
            'fromRow' => RailwayTransit::find()->count()-1,
           // 'toRow' => 10032,
        ];
       // dump($config['fromRow'],1);
        ParserExcel::getInstance($config)->parse();
        die();
 }

    public function actionDebug()
    {
        RTFirms::allTo();
    }

    public function actionReplaceName()
    {
        static::replaceName(\app\models\RTFirms::className());
    }

    private static function replaceName($modelName, $attribute = 'name')
    {
        foreach ($modelName::find()->all() as $instance) {
            $instance->$attribute = Main::stringToProperName($instance->$attribute);
            $instance->save();
        }
    }

    //contracts customerFirms
}
