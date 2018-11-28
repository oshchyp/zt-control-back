<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.08.2018
 * Time: 11:17
 */

namespace app\modules\api\controllers;

use app\models\filter\RailwayTransitFilter;
use app\models\filter\RTFilter;
use app\models\RailwayTransit;
use app\models\RailwayTransitMultiSave;
use app\modules\api\models\RTParserFromDownloadXLS;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class RailwayTransitController extends Controller
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
                    'actions' => ['index','view','list','extra-data','completed'],
                    'allow' => true,
                    'roles' => ['railroad-roads/view'],
                ],
                [
                    'actions' => ['update','complete', 'parser-xls'],
                    'allow' => true,
                    'roles' => ['railroad-roads/update'],
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['railroad-roads/delete'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['railroad-roads/create'],
                ]
            ],
        ];

        return $behaviors;
    }

    public $rowsPerPageDefault = 10;

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
        $status = $completed_status ? RailwayTransit::STATUS_ID_COMPLETED : RailwayTransit::STATUS_ID_NEW;
        $this->filter(RTFilter::className());
        $this->getQuery()->andWhere(['statusID'=>$status]);
        if (!$this->getQuery()->orderBy){
            $sortField = $status == RailwayTransit::STATUS_ID_COMPLETED ? 'railwayTransit.updated_at' : 'railwayTransit.id';
            $this->getQuery()->orderBy(
                [$sortField => SORT_DESC]);
        }
        $this->setPagination();
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

    public function actionExtraData(){
        $this->responseData = RailwayTransit::extraDataToSave();
        $this->validationInfo = null;
        $this->setResponseParams(static::RESPONSE_PARAMS_VIEW_DATA_ALL);
    }

    public function actionParserXls(){
        $model = new RTParserFromDownloadXLS();
        $this->responseData = $model;
        $model->attributes = array_merge($this->getRequestData(),['xlsFile'=>UploadedFile::getInstanceByName('xlsFile')]);
        $model->runParser();
        $this->setResponseParams(static::RESPONSE_PARAMS_SAVE);
    }

}