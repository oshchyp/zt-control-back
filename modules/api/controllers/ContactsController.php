<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.08.2018
 * Time: 10:39
 */

namespace app\modules\api\controllers;


use app\models\Contacts;
use yii\filters\AccessControl;

class ContactsController extends Controller
{

    public function init(){
        $this->setResource(new Contacts());
        return parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index','view'],
                    'allow' => true,
                    'roles' => ['firms/update','firms/create'],
                ]
            ]
        ];

        return $behaviors;
    }

    public function actionIndex(){
        $this->activeIndex();
    }
}