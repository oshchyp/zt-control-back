<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 15:37
 */

namespace app\models\filter;


class FullFilter extends Filter
{

    public $exactMath;

    public $searchStr;

    public $columnSearchStr;

    public function rules(){
        $rules = parent::rules();
        $rules[]=[['exactMath','searchStr','columnSearchStr'],'safe'];
        return $rules;
    }

    public function filterClasses (){
        return [
            ['exactMath',\app\models\filter\ExactMatch::className()],
            ['searchStr',\app\models\filter\SearchStr::className()],
            ['columnSearchStr', \app\models\filter\ColumnSearchStr::className()]
        ];
    }

    public function filter(){
        foreach ($this->filterClasses() as $item){
            $model = $item[1];
            $attribute = $item[0];
            $params = ['query' => $this->query];
            if ($this->$attribute && is_array($this->$attribute)){
                $params += $this->$attribute;
            }

            $model::loadModel($params);
        }
    }
}