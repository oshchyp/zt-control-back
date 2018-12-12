<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 13:57
 */

namespace app\modules\api\controllers;

use app\components\filter\FilterDataInterface;
use app\modules\api\models\FirmManagers;
use app\modules\api\models\FirmManagersFilter;
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

    /**
     * @return FilterDataInterface
     */
    public function getFilterInstance(): FilterDataInterface
    {
        return FirmManagersFilter::instance();
    }
}