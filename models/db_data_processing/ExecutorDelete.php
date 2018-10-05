<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 02.10.2018
 * Time: 15:22
 */

namespace app\models\db_data_processing;


use app\models\ExecutorFirms;
use app\models\ForwarderFirms;
use app\models\RailwayTransit;

class ExecutorDelete extends ExecutorFirms
{

    public static function  toForwarder (){
        foreach (static::find()->all() as $instance){
            if (!ForwarderFirms::find()->where(['uid'=>$instance->uid])->one()){
                $ForwarderInstance = new ForwarderFirms();
                $ForwarderInstance->attributes = [
                    'uid' => $instance->uid,
                    'name' => $instance->name
                ];
                $ForwarderInstance->save();
            }
        }
    }

    /**
     * @param array $r
     */
    public static function setForwarderFromExecutor($r=[]){
        $r = !$r ? static::findRailwayTransitWithoutForwarder() : $r;
        foreach ($r as $object){
            $object->forwarderFirmUID = $object->executorFirmUID;
            $object->save();
        }
    }

    public static function findRailwayTransitWithoutForwarder(){
        return  RailwayTransit::find()->innerJoinWith('executorFirm')->joinWith('forwarderFirm')
            ->where(['is','forwarderFirms.uid',null])->with(['forwarderFirm'])->all();
    }

}