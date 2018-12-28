<?php

namespace app\controllers;

use app\models\json_parser\Contracts;
use app\models\json_parser\Parser;
use app\models\xls\FirmsParser;
use app\models\xls\ParserActiveRecord;
use http\Exception;
use yii\filters\AccessControl;
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
        die('404');
    }

    public function actionIndex($url='',$url1='',$url2='')
    {
        return require \Yii::getAlias('@app') . '/web/index.html';
    }

}
