<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.10.2018
 * Time: 11:26
 */

namespace app\commands;



use app\models\firms\FirmsByElevatorsAsSave;

class DebugController extends \yii\console\Controller
{

    /**
     *
     */
    public function actionIndex(){
//         $model = new FirmsByElevatorsAsSave();
//         $model -> uid = 'erfergerg_'.time();
//         $model->name = '45t';
//         dump($model->save());
      dump(4 & 1);


    }

}