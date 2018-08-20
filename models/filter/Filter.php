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

    public $params;

    public $filterAttributes;

    public function filter(){
        if ($this->getFilterAttributes() && is_array($this->getFilterAttributes())){
            foreach ($this->getFilterAttributes() as $attr){
                if (method_exists($this,$method = $attr.'Filter')){
                    $this->$method();
                } else {
                    $this->filterByProperty($attr);
                }
            }
        }
    }

    public static function loadModel($attributes=[]){
        $instance = new static();
        $instance->attributes = $attributes;
        $instance->validate();
        if (!$instance->hasErrors()) {
            $instance->filter();
        }
        return $instance;
    }





}