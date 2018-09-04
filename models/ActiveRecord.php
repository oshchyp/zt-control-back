<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.09.2018
 * Time: 15:08
 */

namespace app\models;


use app\models\helper\Main;
use yii\helpers\ArrayHelper;

class ActiveRecord extends \yii\db\ActiveRecord
{

    public static $allInstances = null;

    public $addInstanceAfterSave = false;

    public static function getAllInstances(){
        if (static::$allInstances === null){
            static::$allInstances = static::find()->all();
        }
        return static::$allInstances;
    }

    public static function getUidAttrName(){
        return 'uid';
    }

    public function setUid(){
        $uidAttrName = static::getUidAttrName();
        if (!$this->$uidAttrName){
            $array = ArrayHelper::toArray(static::getAllInstances());
            $this->$uidAttrName = Main::generateUidUniq($array ? ArrayHelper::getColumn($array,$uidAttrName) : []);
        }
    }


    public function addThisToAllInstances(){
        if ($this->addInstanceAfterSave){
            $object = clone $this;
            $object->setIsNewRecord(false);
            static::$allInstances[] = $object;
        }
    }

    public function setEventsParsing(){
         $events = [
            [
                0=>static::EVENT_BEFORE_INSERT,
                1=>function($model){
                    $model->sender->addInstanceAfterSave = true;
                    return true;
                }
            ],
            [
                0=>static::EVENT_BEFORE_VALIDATE,
                1=>function($model){
                    $model->sender->setUid();
                    return true;
                }
            ],
            [
                0=>static::EVENT_AFTER_INSERT,
                1=>function($model){
                    $model->sender->addThisToAllInstances();
                }
            ]
        ];
        foreach ($events as $item){
            $this->on($item[0],$item[1]);
        }
        return $this;
    }

    public static function getInstanceByAttrValue($attrValue,$attrName){
        if ($instances = static::getAllInstances()){
            foreach ($instances as $object){
               if ($object->$attrName == $attrValue){
                   return $object;
                }
            }
        }
        return new static();
    }

}