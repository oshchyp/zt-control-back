<?php

namespace app\modules\api\controllers;

use app\models\Contacts;
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
        $this->responseExtraData = [
            'contacts' => Contacts::find()->all(),
            'regions' => ArrayHelper::toArray(Regions::find()->with('points')->all(), [Regions::className() => Regions::viewFields()]),
            'posts' => Posts::findAll()
        ];
       $this->responseData = Firms::find()->with(Firms::viewRelations())->orderBy(['id' => SORT_DESC]) ->all();
    }

     public function actionList(){
         $this->responseExtraData = [
             'contacts' => Contacts::find()->all(),
             'regions' => ArrayHelper::toArray(Regions::find()->with('points')->all(), [Regions::className() => Regions::viewFields()]),
             'posts' => Posts::findAll()
         ];
         $query = Firms::find()->with(Firms::viewRelations())->orderBy(['id' => SORT_DESC]);

         FullFilter::loadModel(['query'=>$query] + ArrayHelper::getValue($this->getRequestData(),'filter',[]));

         $this->setPagination(clone $query)->setPaginationParamsToQuery($query)->setPaginationParamsToExtraData();
         //  dump($query->createCommand()->getRawSql(),1);
         $this->responseData = $query->all();
     }

  //   public function

    public function actionCreate()
    {
        $this->resource->uid = Main::generateUid();
        $this->activeCreate();
    }

    public function actionUpdate($id)
    {
        $this->activeUpdate($id);

    }

}
