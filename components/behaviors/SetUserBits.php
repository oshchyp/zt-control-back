<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 26.12.2018
 * Time: 11:39
 */

namespace app\components\behaviors;


use app\models\ZlataElevators;
use yii\base\Behavior;

class SetUserBits extends Behavior
{

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }


    /**
     * @param $event
     */
    public function beforeValidate($event)
    {
        $bit = \Yii::$app->user->identity->elevatorBit;
        if (!ZlataElevators::find()->where(['bit'=>$bit])){
            $bit = 1;
        }
        $this->owner->elevatorBit = $bit;
    }

}