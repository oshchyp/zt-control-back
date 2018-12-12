<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:01
 */

namespace app\modules\api\controllers;


use app\modules\api\models\CultureAsExtraData;
use app\modules\api\models\Farms;
use app\modules\api\models\FarmsFilter;
use app\modules\api\models\FarmsToDelete;
use app\modules\api\models\FarmsToSave;
use app\modules\api\models\FirmsAsExtraData;
use app\modules\api\models\PointsAsExtraData;
use app\modules\api\models\RegionsAsExtraData;
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

    public function actionIndex()
    {
        $this->addResponseExtraData(FirmsAsExtraData::instance(),'firms');
        $this->addResponseExtraData(RegionsAsExtraData::instance(),'regions');
        $this->addResponseExtraData(PointsAsExtraData::instance(),'points');
        $this->addResponseExtraData(CultureAsExtraData::instance(),'cultures');

        $this->setResource(Farms::instance());

        $this->filter(FarmsFilter::instance());
        $this->getQuery()->addOrderBy(['farms.id' => SORT_DESC]);

        $this->setPagination();
        $this->activeIndex();
    }

    public function actionList(){
        $this->actionIndex();
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