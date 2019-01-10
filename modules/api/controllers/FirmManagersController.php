<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 13:57
 */

namespace app\modules\api\controllers;

use app\components\filter\FilterDataInterface;
use app\models\asextradata\ZlataElevatorsAsExtraData;
use app\models\firmManagers\FirmManagers;
use app\models\firmManagers\FirmManagersAsSave;
use app\models\firmManagers\FirmManagersFilter;
use yii\filters\AccessControl;

class FirmManagersController extends FirmPeoplesController
{

    public function init()
    {
        $this->setResource(new FirmManagers());
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index', 'list'],
                    'allow' => true,
                    'roles' => ['firm-managers/view'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['firm-managers/create'],
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['firm-managers/update'],
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['firm-managers/delete'],
                ]
            ]
        ];

        return $behaviors;
    }


    public function beforeAction($action)
    {
        if (in_array($action->id,['create','update'])){
            $this->setResource(new FirmManagersAsSave());
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * @return FilterDataInterface
     */
    public function getFilterInstance(): FilterDataInterface
    {
        return FirmManagersFilter::instance();
    }

    public function actionIndex(){
        $this->activeIndex();
    }

    public function actionList()
    {
        $this->addResponseExtraData(ZlataElevatorsAsExtraData::instance(),'elevators');
        $this->filter(FirmManagersFilter::instance());
        $this->setPagination();
        $this->activeIndex();
    }
}