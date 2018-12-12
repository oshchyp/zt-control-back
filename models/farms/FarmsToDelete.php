<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:24
 */

namespace app\models\farms;


use app\components\models\ModelAsResourceInterface;

class FarmsToDelete extends \app\models\Farms implements ModelAsResourceInterface
{

    /**
     *
     */
    public function afterDelete()
    {
        parent::afterDelete();
        \app\models\FarmCultures::deleteAll(['farmUID'=>$this->uid]);
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}