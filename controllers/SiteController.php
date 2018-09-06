<?php

namespace app\controllers;

use app\models\Firms;
use app\models\json_parser\Contracts;
use app\models\json_parser\Parser;
use app\models\xls\FirmsParser;
use app\models\xls\ParserActiveRecord;
use Yii;
use yii\filters\AccessControl;
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
            'except' => ['index','error'],
            'denyCallback' => function(){
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

    public function actionXls(){
      ini_set('memory_limit','1024M');
      \app\models\xls\Firms::deleteAllInfo();
      $parserObject = new \app\models\xls\ParserExcel();
      $parserObject->file = '@app/files/xls/firms.xlsx';
      $parserObject->model = \app\models\xls\Firms::className();
      $parserObject->parse();
      \app\models\xls\Firms::removeSuperfluous();
    }

    public function actionDebug(){
      //  (new Parser())->parse();
    }

}
