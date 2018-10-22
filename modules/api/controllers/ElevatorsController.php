<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 13.07.2018
 * Time: 16:26
 */

namespace app\modules\api\controllers;

use app\models\Elevators;
use app\modules\api\models\LogisticAPI;
use yii\filters\AccessControl;

class ElevatorsController extends Controller
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
                    'roles' => ['elevators/view'],
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['elevators/update'],
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['elevators/delete'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['elevators/create'],
                ]
            ]
        ];

        return $behaviors;
    }



    public function actionIndex()
    {
        (new LogisticAPI())->elevators()->createResponseApi($this);
    }

}