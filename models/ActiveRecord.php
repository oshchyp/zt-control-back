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
use yii\helpers\FileHelper;

class ActiveRecord extends \yii\db\ActiveRecord implements RestModelInterface
{

    public static $allInstances = null;

    public static $logPath;

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


    public function getRestValidators()
    {
        $result=[];
        if ($validators=$this->getActiveValidators()){
            foreach ($validators as $object){
                foreach ($object->attributes as $attr){
                  //  dump($attr,1);
                    $result[$attr][]=[
                        'type'=>static::validatorName($object),
                        'message' => str_replace('{attribute}',$this->getAttributeLabel($attr),$object->message),
                    ];
                }
            }
        }
        return $result;
    }

    public static function validatorName($object){
        $names = [
            'yii\validators\RequiredValidator' => 'required',
            'yii\validators\NumberValidator'=>'number',
            'yii\validators\StringValidator'=>'string',
        ];
        $deffName = function($obj){

            $n = explode('\\',$obj->className());
            array_pop($n);
            return str_replace('Validator','',$n);
        };
        return key_exists($object->className(),$names) ? $names[$object->className()] : $deffName($object);
    }
}