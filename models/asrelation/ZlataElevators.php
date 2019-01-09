<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.01.2019
 * Time: 13:17
 */

namespace app\models\asrelation;


use app\components\models\ModelAsRelationInterface;

class ZlataElevators extends \app\models\ZlataElevators implements ModelAsRelationInterface
{

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}