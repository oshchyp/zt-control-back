<?php

namespace app\modules\api\models;

use app\components\models\ModelAsResourceInterface;

class Users extends \app\models\Users implements ModelAsResourceInterface
{


    public function fields()
    {
        return [
            'id', 'firstName', 'lastName', 'phone', 'permissions', 'actions',
         ];
    }

    /**
     * @return array
     */
    public static function relations()
    {
       return [];
    }

    /**
     * @return mixed
     */
    public function getRestValidators()
    {
        return [];
    }
}
