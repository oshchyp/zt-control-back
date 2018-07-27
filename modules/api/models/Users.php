<?php

namespace app\modules\api\models;

class Users extends \app\models\Users
{
    public function fields()
    {
        return [
            'id', 'firstName', 'lastName', 'phone', 'permissions', 'actions',
         ];
    }
}
