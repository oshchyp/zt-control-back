<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.10.2018
 * Time: 11:26
 */

namespace app\commands;


use app\models\DateSet;
use app\models\RailwayTransit;
use app\models\RailwayTransitMultiSave;
use app\models\SMSApi;

class DebugController extends \yii\console\Controller
{

    public function actionIndex(){
        $resultSend = SMSApi::send('380992345593','test');
        dump($resultSend,1);
    }

}