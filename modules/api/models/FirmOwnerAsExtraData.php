<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 16:26
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\ModelAsExtraData;

class FirmOwnerAsExtraData extends \app\models\FirmOwners implements ModelAsExtraData
{


    /**
     * @return array
     */
    public function fields()
    {
        return [ 'uid', 'name', 'phone', 'email'];
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }

}