<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 14:42
 */

namespace app\models\filter;


use yii\db\ActiveQuery;

interface FilterDataInterface
{

    /**
     * @param array $params
     * @param ActiveQuery|null $query
     * @return mixed
     */
    public static function search($params = [], ActiveQuery $query = null);

}