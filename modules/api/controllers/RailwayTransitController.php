<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.08.2018
 * Time: 11:17
 */

namespace app\modules\api\controllers;


use app\models\Contracts;
use app\models\Culture;
use app\models\RailwayTransit;
use app\models\RailwayTransitMultiSave;
use app\models\Stations;

class RailwayTransitController extends Controller
{

    public function init()
    {
        $this->setResource(new RailwayTransit());
        parent::init();
    }

    /**
     * @return array
     */
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors[] = [
//            'class' => AccessControl::className(),
//            'rules' => [
//                [
//                    'actions' => ['index','view'],
//                    'allow' => true,
//                    'roles' => ['roads/view'],
//                ]
//
//            ],
//        ];
//
//        return $behaviors;
//    }

    public function actionIndex()
    {
        $this->responseExtraData = [
            'contracts' => Contracts::find()->all(),
            'cultures' => Culture::find()->all(),
            'stations' => Stations::find()->all(),
        ];
        $this->activeIndex();
    }

    public function actionView($id)
    {
        $this->activeView($id);
    }

    public function actionCreate()
    {
        $this->responseData = RailwayTransitMultiSave::getInstance($this->getRequestData());
        $this->responseData->create();
        $this->setResponseParams(static::RESPONSE_PARAMS_SAVE);
    }

    public function actionUpdate()
    {
        $this->responseData = RailwayTransitMultiSave::getInstance($this->getRequestData());
        $this->responseData->update();
        $this->setResponseParams(static::RESPONSE_PARAMS_SAVE);
    }

    public function actionDelete($id)
    {
        $this->activeDelete($id);
    }

}