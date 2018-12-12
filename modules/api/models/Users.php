<?php

namespace app\modules\api\models;

use app\modules\api\models\interfaces\ModelAsResource;

class Users extends \app\models\Users implements ModelAsResource
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
