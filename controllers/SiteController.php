<?php

namespace app\controllers;

use app\models\filter\RailwayTransitFilter;
use app\models\Firms;
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
                    'ips' => ['213.231.13.123']
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
        $filterParams = [
          //  'stringForSearchAll' => 'луи Дрейфус',
          //  'wagonNumber'=>'95641437',
           // 'weight'=>'5',
//            'destinationStation' => 'одесса-порт-экс.',
//            'departureStation' => 'черноморская-экс ТИС',
//            'product' => 'кукурудза українського походження',
          //  'different' => '69.40',
           // 'datePlane' => '2022',
            'status' => 'новый'
            //Завершенный
        ];
        $query = RailwayTransitFilter::find($filterParams)->asArray()->limit(5);
        dump($query->createCommand()->getRawSql());
        dump($query->groupBy('statusID')->count(),1);
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
            'model' => \app\models\xls\RailwayTransitContracts::className(),
            'activeSheet' => 2,
            'fromRow' => 1,
            'toRow' => 10032,
        ];
        ParserExcel::getInstance($config)->parse();
        die();
        $parserObject = new \app\models\xls\ParserExcel();
        $parserObject->file = '@app/files/xls/railway.xls';
//        $parserObject->model = \app\models\xls\RailwayTransit::className();
//        $parserObject->setActiveSheet(2);
//        $parserObject->fromRow = RailwayTransit::find()->count() - 1;
//        $parserObject->toRow = $parserObject->fromRow + 500;


        $parserObject->model = \app\models\xls\RailwayTransitContracts::className();
        $parserObject->setActiveSheet(2);
        $parserObject->fromRow = 4000;
        $parserObject->toRow = $parserObject->fromRow + 1000;

        $parserObject->parse();


    }

    public function actionDebug()
    {
        // $ins = Firms::find()->all();

//         foreach ($ins as $item){
//             $item->test = 7;
//             $item->save();
//         }
    }

}
