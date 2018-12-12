<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 11:36
 */

namespace app\modules\api\controllers;


use app\components\filter\FilterDataInterface;
use app\modules\api\models\FirmOwners;
use app\modules\api\models\FirmOwnersFilter;
use yii\filters\AccessControl;

class FirmOwnersController extends FirmPeoplesController
{

    public function init()
    {
        $this->setResource(new FirmOwners());
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
                    'roles' => ['firm-owners/view'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['firm-owners/create'],
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['firm-owners/update'],
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['firm-owners/delete'],
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
        return FirmOwnersFilter::instance();
    }

}