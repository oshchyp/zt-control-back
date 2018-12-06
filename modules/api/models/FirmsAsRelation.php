<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 15:08
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsRelation;

class FirmsAsRelation extends \app\models\Firms implements ModelAsRelation
{

    public function fields()
    {
        return ['uid','name','rdpu', 'square'];
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}