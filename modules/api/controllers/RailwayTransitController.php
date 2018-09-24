<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.08.2018
 * Time: 11:17
 */

namespace app\modules\api\controllers;

use app\models\filter\RailwayTransitFilter;
use app\models\RailwayTransit;
use app\models\RailwayTransitMultiSave;

class RailwayTransitController extends Controller
{

    public function init()
    {
        $this->setResource(new RailwayTransit());
        parent::init();
    }

    /**
     * @param null $completed_status
     */
    public function actionIndex($completed_status = null)
    {
        RailwayTransit::setFormat('d.m.Y');
        $status = $completed_status ? RailwayTransit::STATUS_ID_COMPLETED : RailwayTransit::STATUS_ID_NEW;
        $this->responseExtraData = RailwayTransit::extraDataToSave();
        $this->getQuery()->andWhere(['statusID'=>$status])->orderBy(['id' => SORT_DESC]);
        $this->filter(new RailwayTransitFilter());
        $this->setPagination();
      //  dump($this->getQuery()->createCommand()->getRawSql());
        $this->activeIndex();
    }

    public function actionList(){
        $this->actionIndex();
    }

    public function actionCompleted(){
        $this->actionIndex(true);
    }

    /**
     * @param $id
     */
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

    public function actionDelete($id=null)
    {
        $this->activeDelete($id);
    }

    public function actionComplete(){
        RailwayTransit::updateAll(['statusID'=>RailwayTransit::STATUS_ID_COMPLETED],['in','id',$this->getRequestData()]);
        $this->setResponseParams(static::RESPONSE_PARAMS_VIEW_DATA_ALL);
    }

}