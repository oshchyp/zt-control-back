<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 13:21
 */

namespace app\models\filter;


class SearchStr extends Filter
{

    public $q=0;

    public $search;

    public function rules(){
        $rules = parent::rules();
        array_push($rules,[['search'],'string']);
        array_push($rules,[['search'],'required']);
        return $rules;
    }

    public function filterByProperty($attr){
        $method = $this->q === 0 ? 'andWhere' : 'orWhere';
        $this->query->$method(['like', $this->getColumnName($attr), $this->search]);
        $this->q++;
    }

}