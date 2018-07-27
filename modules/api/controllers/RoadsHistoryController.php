<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 16.07.2018
 * Time: 16:34
 */

namespace app\modules\api\controllers;


use app\modules\api\models\LogisticAPI;
use yii\filters\AccessControl;

class RoadsHistoryController extends Controller
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
                    'roles' => ['roads-history/view'],
                ]

            ],
        ];

        return $behaviors;
    }

    public function actionIndex(){
        (new LogisticAPI())->roadsHistory()->createResponseApi($this);
    }

}