<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:01
 */

namespace app\modules\api\controllers;


use app\components\bitAccess\BitAccessFilter;
use app\models\asextradata\CultureAsExtraData;
use app\models\farms\Farms;
use app\models\farms\FarmsFilter;
use app\models\farms\FarmsToDelete;
use app\models\farms\FarmsToSave;
use app\models\asextradata\FirmsAsExtraData;
use app\models\asextradata\PointsAsExtraData;
use app\models\asextradata\RegionsAsExtraData;
use app\models\zlataElevators\ZlataElevatorFinder;
use yii\filters\AccessControl;

class FarmsController extends Controller
{

    public function init()
    {
      //  $this->setResource(new Farms());
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index','list'],
                    'allow' => true,
                    'roles' => ['farms/view'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['farms/create'],
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['farms/update'],
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['farms/delete'],
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex($elevatorID=null)
    {
        $this->addResponseExtraData(FirmsAsExtraData::instance(),'firms');
        $this->addResponseExtraData(RegionsAsExtraData::instance(),'regions');
        $this->addResponseExtraData(PointsAsExtraData::instance(),'points');
        $this->addResponseExtraData(CultureAsExtraData::instance(),'cultures');

        $this->setResource(Farms::instance());

        $this->filter(FarmsFilter::instance());
        $this->getQuery()->addOrderBy(['farms.id' => SORT_DESC]);
        if ($elevatorID){
             BitAccessFilter::getInstance($this->getQuery(),'firmsBitAccess.elevatorBit',ZlataElevatorFinder::findBitByID($elevatorID))->filter();
        }
        $this->setPagination();

        $this->activeIndex();
    }

    public function actionList($elevatorID=null){
        $this->actionIndex($elevatorID);
    }

    public function actionCreate()
    {
        $this->setResource(FarmsToSave::instance());
        $this->activeCreate();
    }

    public function actionUpdate($id)
    {
        $this->setResource(FarmsToSave::instance());
        $this->activeUpdate($id);
    }

    public function actionDelete($id=null)
    {
        $this->setResource(FarmsToDelete::instance());
        $this->activeDelete($id);
    }

}