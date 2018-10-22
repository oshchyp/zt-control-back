<?php

namespace app\modules\api\controllers;

use app\models\ActiveRecord;
use app\models\Contacts;
use app\models\Culture;
use app\models\Elevators;
use app\models\Firms;
use app\models\firmsFilter\ColumnSearchStr;
use app\models\firmsFilter\FullFilter;
use app\models\firmsFilter\SearchStr;
use app\models\helper\Main;
use app\models\Posts;
use app\models\Regions;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

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

    public function getResponseData(){
        return ArrayHelper::toArray($this->responseData, [Firms::className() => Firms::viewFields()]);
    }

    public function actionIndex()
    {

     //   dump(\Yii::$app->request->getHeaders(),1);

        Regions::$getAllPoints = true;
        $this->responseExtraData = [
            'contacts' => Contacts::find()->all(),
            'regions' => ArrayHelper::toArray(Regions::find()->with('points')->all(), [Regions::className() => Regions::viewFields()]),
            'posts' => Posts::findAll(),
            'cultures' => Culture::find()->all(),
            'elevators' => Elevators::find()->all(),
            'points' => Regions::getAllPoints(),
            'distributionStatuses' => Firms::distributionStatuses()
        ];
       $this->responseData = Firms::find()->with(Firms::viewRelations())->orderBy(['id' => SORT_DESC]) ->limit(10) ->all();
    }

    public function actionView($id)
    {
        parent::activeView($id);
    }

     public function actionList(){
         $this->responseExtraData = [
             'contacts' => Contacts::find()->all(),
             'regions' => ArrayHelper::toArray(Regions::find()->with('points')->all(), [Regions::className() => Regions::viewFields()]),
             'posts' => Posts::findAll(),
             'cultures' => Culture::find()->all()
         ];
         $query = Firms::find()->with(Firms::viewRelations())->orderBy(['id' => SORT_DESC]);

         FullFilter::loadModel(['query'=>$query] + ArrayHelper::getValue($this->getRequestData(),'filter',[]));

         $this->setPagination(clone $query)->setPaginationParamsToQuery($query)->setPaginationParamsToExtraData();
         $this->responseData = $query->all();
     }

  //   public function

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
