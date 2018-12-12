<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 12.12.2018
 * Time: 15:11
 */

namespace app\components\validators;


trait ValidatorTrait
{

    public function arrayValidate($attribute){
        if (!is_array($this->$attribute)) {
            return false;
        }
        if (is_array($this->$attribute) && $this->$attribute){
            foreach ($this->$attribute as $v){
                if (!is_string($v) || is_integer($v)){
                    return false;
                }
            }
        }
        return true;
    }

}