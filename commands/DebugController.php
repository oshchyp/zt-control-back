<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.10.2018
 * Time: 11:26
 */

namespace app\commands;



class DebugController extends \yii\console\Controller
{

    /**
     *
     */
    public function actionIndex(){
        $models = \app\models\Firms::find()->where('elevatorBit & 6')->all();
        dump($models);
    }

}