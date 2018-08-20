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

        $pat = Yii::getAlias('@app').'/logs/api';
        $contentDir = [];
        if (Yii::$app->request->get('pat')) {
            $pat = base64_decode(Yii::$app->request->get('pat'));
        }
        if (is_file($pat)) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return json_decode(file_get_contents($pat));
        } elseif (is_dir($pat)) {
            $scanDir = scandir($pat);
            if ($scanDir) {
                foreach ($scanDir as $v) {
                    if (is_dir($pat.'/'.$v) || is_file($pat.'/'.$v)) {
                        $contentDir[] = [
                        'name' => $v,
                        'link' => base64_encode($pat.'/'.$v),
                    ];
                    }
                }
            }
        }
        //  dump($contentDir, 1);

        return $this->render('index', ['contentDir' => $contentDir]);
    }

    public function actionError()
    {
        return $this->render('error');
    }

    public function actionXls(){
      //  \app\models\xls\Parser::getInstance(['activeSheet'=>1,'model' => \app\models\xls\Firms::className(),'column'=>\app\models\xls\Firms::columnXls(),'filePath'=>'@app/files/xls/firms.xlsx','ignoreRows'=>[1]])->loadDocumentObject()->parse();
    }

    public function actionDebug(){
      //  (new Parser())->parse();
    }

}
