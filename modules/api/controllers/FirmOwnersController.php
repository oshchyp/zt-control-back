<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 11:36
 */

namespace app\modules\api\controllers;


use app\modules\api\models\FirmOwners;
use yii\filters\AccessControl;

class FirmOwnersController extends Controller
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

    public function actionIndex(){
        $this->activeIndex();
    }

    public function actionList()
    {
        $this->activeIndex();
        $this->setPagination();
    }

    public function actionUpdate($id){
        $this->activeUpdate($id);
    }

    public function actionCreate(){
        $this->activeCreate();
    }

    public function actionDelete($id=null){
        $this->activeDelete($id);
    }

}