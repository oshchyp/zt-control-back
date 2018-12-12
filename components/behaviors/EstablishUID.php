<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 11:06
 */

namespace app\components\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class EstablishUID extends Behavior
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
        if (!$this->owner->uid){
            $this->owner->uid = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        }
    }

}