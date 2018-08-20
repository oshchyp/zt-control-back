<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 13:21
 */

namespace app\models\filter;


class FilterSearchStr extends Filter
{

    public $q=0;

    public $search;

    public function rules(){
        return [
            [['search'],'string'],
            [['search'],'required'],
        ];
    }

    public function filterByProperty($attr){
        $method = $this->q === 0 ? 'andWhere' : 'orWhere';
        $this->query->$method(['like', $attr, '%'.$this->search.'%']);
        $this->q++;
    }

}