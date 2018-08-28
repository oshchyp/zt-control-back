<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 14:53
 */

namespace app\models\filter;


class ColumnSearchStr extends Filter
{

    public function filterByProperty($attr){
        if ($this->$attr) {
            $this->query->andWhere(['like', $this->getColumnName($attr), $this->$attr]);
        }
    }

}