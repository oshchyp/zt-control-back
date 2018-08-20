<?php

namespace app\modules\api\controllers;

use app\models\Contacts;
use app\models\Firms;
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
                    'actions' => ['index', 'view'],
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
        $query = Firms::find()->with(Firms::viewRelations())->orderBy(['id' => SORT_DESC]);
        $this->setPagination(clone $query)->setPaginationParamsToQuery($query)->setPaginationParamsToExtraData();
        $this->responseData = $query->all();
     }

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
