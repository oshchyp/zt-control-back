<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 15:32
 */

namespace app\models\firmsFilter;


use yii\base\Model;

class FullFilter extends \app\models\filter\FullFilter
{

    public function setSearchStr ($value){
        $this->searchStr = [];
        $this->searchStr['search'] = $value;
    }

    public function setFilters($value){
        $this->exactMath=[];
        $this->exactMath = $value;
    }

    public function filterClasses (){
        return [
            ['exactMath',ExactMatch::className()],
            ['searchStr',SearchStr::className()],
            ['columnSearchStr', ColumnSearchStr::className()]
        ];
    }


}