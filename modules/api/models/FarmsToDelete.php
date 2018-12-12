<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 13:24
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsResource;

class FarmsToDelete extends \app\models\Farms implements ModelAsResource
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