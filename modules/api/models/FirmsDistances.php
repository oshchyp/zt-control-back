<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 12:42
 */

namespace app\modules\api\models;


class FirmsDistances extends \app\models\FirmsDistances
{

    public function fields()
    {
        return array_merge(parent::fields(),['point']);
    }

}