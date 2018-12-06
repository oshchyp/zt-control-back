<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 11:29
 */

namespace app\models\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class PhoneHandling extends Behavior
{

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
        ];
    }


    public function beforeSave($event)
    {
        if ($this->owner->phone) {
            $this->owner->phone = str_replace(['+', '(', ')', ' '], '', $this->owner->phone);
        }
    }

}