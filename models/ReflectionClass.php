<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 29.10.2018
 * Time: 15:34
 */

namespace app\models;


use yii\db\ActiveQuery;

class ReflectionClass extends \ReflectionClass
{

    public static function getInstance($model){
        return new static($model);
    }

    public function relations(){
        $result = [];
        foreach ($this->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $existActiveQueryComment = preg_match('/\*( +)?@return (\\\yii\\\db\\\)?ActiveQuery( .*)?/', $method->getDocComment(),$rt);
            $isGetter = preg_match('/^get.*$/', $method->getName());
            if ($method->getNumberOfParameters() === 0 && $isGetter && $existActiveQueryComment) {
                $result[]=$method;
            }

//            $className = $this->getName();
//            $methodName = $method->getName();
//            if ($method->getNumberOfParameters() === 0 && $isGetter && $className::instance()->$methodName() instanceof ActiveQuery){
//                $result[]=$method;
//            }

        }
        return $result;
    }

    public function relationsId(){
        $result = [];
        /**
         * @var $methodInfo \ReflectionMethod
         */
        foreach ($this->relations() as $methodInfo){
            $name = preg_replace('/get/','',$methodInfo->getName());
            $result[] = lcfirst($name);
        }
        return $result;
    }

}