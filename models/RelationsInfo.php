<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 01.11.2018
 * Time: 09:41
 */

namespace app\models;


use yii\base\Component;

class RelationsInfo extends Component
{

    /**
     * @var \ReflectionClass
     */
    private $_reflection;

    public function init(){
        $this->validateClass();
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflection()
    {
        return $this->_reflection;
    }

    /**
     * @param $reflection
     */
    public function setReflection($reflection)
    {
        $this->_reflection = $reflection;
    }



    /**
     * @param $modelClass
     * @return RelationsInfo
     */
//    public static function getInstance($modelClass){
//        $reflection = new \ReflectionClass($modelClass)->
//        $instance = new static();
//        return $instance;
//    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validateClass(){



        if (!class_exists($this->getModelClass())){
            throw new \Exception('Class not exist');
        } else if (!is_subclass_of($this->getModelClass(),ActiveRecord::className())){
            throw new \Exception('class must inherit from ActiveRecord');
        }
        return true;
    }

    public function getters(){

    }

}