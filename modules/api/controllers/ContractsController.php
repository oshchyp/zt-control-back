<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 16.07.2018
 * Time: 16:26
 */

namespace app\modules\api\controllers;


use app\modules\api\models\LogisticAPI;
use yii\filters\AccessControl;

class ContractsController extends Controller
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
                    'roles' => ['contracts/view'],
                ],
             ]
        ];

        return $behaviors;
    }

    public function actionIndex(){
        (new LogisticAPI())->contracts()->createResponseApi($this);
    }

}