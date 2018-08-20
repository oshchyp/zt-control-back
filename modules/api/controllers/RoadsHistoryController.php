<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 16.07.2018
 * Time: 16:34
 */

namespace app\modules\api\controllers;


use app\modules\api\models\LogisticAPI;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

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
                ],
                [
                    'actions' => ['statistics'],
                    'allow' => true,
                    'roles' => ['roads-history/statistics'],
                ]

            ],
        ];

        return $behaviors;
    }

    public function actionIndex(){
        (new LogisticAPI())->roadsHistory()->createResponseApi($this);
    }

    public function actionStatistics(){
        (new LogisticAPI())->setMethodRequest(Yii::$app->request->method)->setRequestData($this->getRequestData())->roadsHistoryStatistics()->createResponseApi($this);
        $this->responseData = ArrayHelper::toArray($this->responseData);
        $this->responseData[0]['reqData'] = $this->getRequestData();
    }

}