<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 26.10.2018
 * Time: 16:37
 */

namespace app\modules\api1\controllers;


use app\models\filter\RTFilter;
use app\models\RailwayTransit;

class RailwayTransitController extends Controller
{

    public $modelClass = 'app\models\RailwayTransit';

    public function listModelClass()
    {
        return RTFilter::className();
    }



    public function actionTest(){
        return [
            'wefwef','wefewf'
        ];
    }



}