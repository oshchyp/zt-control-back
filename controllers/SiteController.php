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
                    'ips' => ['94.74.94.127']
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
      //  \app\models\xls\Parser::getInstance(['activeSheet'=>1,'model' => \app\models\xls\Firms::className(),'column'=>\app\models\xls\Firms::columnXls(),'filePath'=>'@app/files/xls/firms.xlsx','ignoreRows'=>[1]])->loadDocumentObject()->parse();
    }

    public function actionDebug(){
      //  (new Parser())->parse();
    }

}
