<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.09.2018
 * Time: 15:08
 */

namespace app\models;


use app\components\helper\Main;

class ActiveRecord extends \yii\db\ActiveRecord implements RestModelInterface
{

    /**
     * @return string
     */
    public static function getUidAttrName(){
        return 'uid';
    }

    public function setUid(){
        $uidAttrName = static::getUidAttrName();
        if (!$this->$uidAttrName){
            $this->$uidAttrName = Main::generateUid();
        }
    }


    /**
     * @return array
     */
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

    /**
     * @param $object
     * @return mixed
     */
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