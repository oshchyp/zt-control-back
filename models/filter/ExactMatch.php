<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 15:07
 */

namespace app\models\filter;


class ExactMatch extends Filter
{
    public function filterByProperty($attr){
        if ($this->$attr) {
            $this->query->andWhere([$this->getColumnName($attr) => $this->$attr]);
        }
    }
}