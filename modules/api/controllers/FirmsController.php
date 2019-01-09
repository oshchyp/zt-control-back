<?php

namespace app\modules\api\controllers;

use app\models\ActiveRecord;
use app\models\Contacts;
use app\models\Culture;
use app\models\Elevators;
use app\models\firms\FirmsByElevators;
use app\models\firms\FirmsByElevatorsAsSave;
use app\models\Points;
use app\models\firmCultures\FirmCulturesTotal;
use app\models\asextradata\FirmManagersAsExtraData;
use app\models\asextradata\FirmOwnersAsExtraData;
use app\models\firms\FirmsFilter;
use app\models\firms\Firms;
use app\components\helper\Main;
use app\models\Posts;
use app\models\asextradata\FirmStatusesAsExtraData;
use app\modules\api\models\Regions;
use yii\filters\AccessControl;

class FirmsController extends Controller
{

public function beforeAction($action)
{
    $res = parent::beforeAction($action);

    if (in_array($action->id,['create','update'])){
        $this->setResource(new FirmsByElevatorsAsSave());
    } else {
        $this->setResource(new FirmsByElevators());
    }

    return $res;
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

    public function actionIndex($elevatorBit=null)
    {

        $this->filter(new FirmsFilter());
        $totalInstance = new FirmCulturesTotal($this->getQuery());
        if ($this->getRequestData('selectedIds')){
             $totalInstance->getQuery()->andFilterWhere(['firms.id'=>$this->getRequestData('selectedIds')]);
        }
        if ($elevatorBit){
            $this->getQuery()->andWhere($elevatorBit.' & firms.elevatorBit');
        }
        $this->getQuery()->addOrderBy(['firms.id' => SORT_DESC]);
        $this->responseExtraData = [
            'contacts' => Contacts::find()->all(),
            'regions' => Regions::find()->with(['points'])->all(),
            'posts' => Posts::findAll(),
            'cultures' => Culture::find()->all(),
            'elevators' => Elevators::find()->all(),
            'points' => Points::find()->all(),
            'distributionStatuses' => Firms::distributionStatuses(),
            'weightTotal' => $totalInstance->weight(),
            'squareTotal' => $totalInstance->square()

        ];
        $this->addResponseExtraData(FirmOwnersAsExtraData::instance(),'firmOwners');
        $this->addResponseExtraData(FirmManagersAsExtraData::instance(),'firmManagers');
        $this->addResponseExtraData(FirmStatusesAsExtraData::instance(),'firmStatuses');

        $this->setPagination();
        $this->activeIndex();

    }

    public function actionList($elevatorBit=null){
      //  die('3edr');
         $this->actionIndex($elevatorBit);
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
