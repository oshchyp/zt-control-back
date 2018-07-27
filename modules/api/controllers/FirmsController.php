<?php

namespace app\modules\api\controllers;

use app\modules\api\models\LogisticAPI;
use yii\filters\AccessControl;

class FirmsController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index','view'],
                    'allow' => true,
                    'roles' => ['firms/view'],
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        (new LogisticAPI())->firms()->createResponseApi($this);
    }
}
