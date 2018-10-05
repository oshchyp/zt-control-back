<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.10.2018
 * Time: 09:40
 */

namespace app\models\db_data_processing;


use app\models\CustomerFirms;
use app\models\ForwarderFirms;
use yii\db\ActiveRecord;

class RTFirms extends \app\models\RTFirms
{

    /**
     * @param ActiveRecord|string $modelDonorClassName it is className
     */
    public static function to ($modelDonorClassName=''){
        foreach ($modelDonorClassName::find()->all() as $instance){
            if (!static::find()->where(['uid'=>$instance->uid])->one()){
                $staticInstance = new static();
                $staticInstance->attributes = [
                    'uid' => $instance->uid,
                    'name' => $instance->name
                ];
                $staticInstance->save();
            }
        }
    }

    public static function  forwarderTo (){
        static::to(ForwarderFirms::className());
    }

    public static function  customerTo (){
        static::to(CustomerFirms::className());
    }

    public static function allTo(){
        static::forwarderTo();
        static::customerTo();
    }

}