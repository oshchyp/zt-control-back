<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.08.2018
 * Time: 14:33
 */

namespace app\models\xls;


use yii\helpers\ArrayHelper;

trait ModelTrait
{

//    public static $instances = null;
//
//    public function rules(){
//        $rules = parent::rules();
//        $rules[] = [array_keys($this->rules),'safe'];
//        return $rules;
//    }
//
//    public function loadModel(){
//        foreach ($this -> rules as $k=>$v){
//            if ($this->$k !== null)
//                $this->$v = $this->$k;
//        }
//    }
//
//    public static function getInstance($data){
//
//        if (static::$instances === null){
//            static::$instances = ArrayHelper::index(static::find()->all(), function ($element) {
//                $attributeName = static::$uniqueField[1];
//                return $element->$attributeName;
//            });
//        }
//        $key = ArrayHelper::getValue($data,static::$uniqueField[0]);
//        return ArrayHelper::getValue(static::$instances,$key,new static());
//    }
}