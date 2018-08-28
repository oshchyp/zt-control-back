<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 12:35
 */

namespace app\models\filter;


class Filter extends FilterAbstract
{

    public $query;

   // public $modelClass;

    public function rules()
    {
        return [
            ['query','queryValidate']
        ];
    }

    public function queryValidate(){
        if (!$this->query || !is_object($this->query)){
            $this->addError('query','Query is not valid');
            return false;
        }
        return true;
    }

    public function filter(){
        if ($this->getFilterAttributes() && is_array($this->getFilterAttributes())){
            $this->setJoins();
            foreach ($this->getFilterAttributes() as $attr){
                if (method_exists($this,$method = $attr.'Filter')){
                    $this->$method();
                } else {
                    $this->filterByProperty($attr);
                }
            }
        }
    }

    public function getColumnName($attr){
        $model = $this->query->modelClass;
        return $model::tableName().'.'.$attr;
    }

    public static function loadModel($attributes=[]){
        $instance = new static();
        $instance->attributes = $attributes;
        if ($attributes && is_array($attributes)){
            foreach ($attributes as $attr=>$value){
                if (method_exists($instance,$method = 'set'.ucfirst($attr))){
                    $instance->$method($value);
                }
            }
        }
        $instance->validate();
        if (!$instance->hasErrors()) {
            $instance->filter();
        }
        return $instance;
    }





}