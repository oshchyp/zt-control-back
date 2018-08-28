<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 15:12
 */

namespace app\models\firmsFilter;


trait CommonTrait
{

    public $name;

    public $rdpu;

    public $director;

    public $region;

    public $point;

    public function rules(){
        $rules = parent::rules();
        $rulesAdd = [];
        foreach ($this->getFilterAttributes() as $attribute){
            $rulesAdd[$attribute] = [$attribute,'string'];
        }
        return $rules+$rulesAdd;
    }



    public function getFilterAttributes(){
        return ['name','rdpu','director','region','point'];
    }
}