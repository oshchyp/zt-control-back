<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 13:41
 */

namespace app\models\filter;


use yii\base\Model;

abstract  class FilterAbstract extends Model
{
    public function filterByProperty($attribute){

    }

    public function getFilterAttributes(){
        return [];
    }
}