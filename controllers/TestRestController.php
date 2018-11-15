<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 26.10.2018
 * Time: 09:50
 */

namespace app\controllers;


use app\models\filter\RTFilter;
use app\models\RailwayTransit;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Response;

class TestRestController extends ActiveController
{

    public $modelClass = 'app\models\RailwayTransit';

//    public function init()
//    {
////        \Yii::$app->request->parsers = [
////            'application/json' => 'yii\web\JsonParser',
////        ];
//        parent::init();
//
//    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['contentNegotiator']['formats']=[
//            'json'=>Response::FORMAT_JSON
//        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['filter'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
        ];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = RTFilter::search(['product.name' => 'пшени'])->limit(5);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}