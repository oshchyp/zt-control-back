<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 27.07.2018
 * Time: 11:13
 */

namespace app\modules\api\controllers;


use app\modules\api\models\LogisticAPI;
use yii\filters\AccessControl;

class RoadsController extends Controller
{

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index','view'],
                    'allow' => true,
                    'roles' => ['roads/view'],
                ]

            ],
        ];

        return $behaviors;
    }

    public function actionIndex(){
        (new LogisticAPI())->roads()->createResponseApi($this);
    }

}