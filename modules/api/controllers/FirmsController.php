<?php

namespace app\modules\api\controllers;

use app\models\ActiveRecord;
use app\models\Contacts;
use app\models\Culture;
use app\models\Elevators;
use app\models\Points;
use app\modules\api\models\FirmsFilter;
use app\modules\api\models\Firms;
use app\models\helper\Main;
use app\models\Posts;
use app\modules\api\models\Regions;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class FirmsController extends Controller
{

    public function init()
    {
        $this->setResource(new Firms());
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index', 'view','list'],
                    'allow' => true,
                    'roles' => ['firms/view'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['firms/create'],
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['firms/update'],
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex()
    {


        $this->responseExtraData = [
            'contacts' => Contacts::find()->all(),
            'regions' => Regions::find()->with(['points'])->all(),
            'posts' => Posts::findAll(),
            'cultures' => Culture::find()->all(),
            'elevators' => Elevators::find()->all(),
            'points' => Points::find()->all(),
            'distributionStatuses' => Firms::distributionStatuses()
        ];

        $this->filter(FirmsFilter::className());
        if (!$this->getQuery()->orderBy){
            $this->getQuery()->orderBy(
                ['id' => SORT_DESC]);
        }
        $this->setPagination();
        $this->activeIndex();

    }

    public function actionList(){
         $this->actionIndex();
    }

    public function actionView($id)
    {
        parent::activeView($id);
    }

    public function actionCreate()
    {
        $this->resource->uid = Main::generateUid();
        $this->activeCreate($this->saveEvents(ActiveRecord::EVENT_AFTER_INSERT));
    }

    public function actionUpdate($id)
    {
        $this->activeUpdate($id,$this->saveEvents(ActiveRecord::EVENT_AFTER_UPDATE));

    }

    public function saveEvents($saveEvent){
        return [
            [
                0=>$saveEvent,
                1=>function($model){
                    $model->sender->saveContacts();
                    $model->sender->saveCultures();
                    $model->sender->saveDistances();
                }
            ]
        ];
    }

}
