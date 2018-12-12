<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 11:22
 */

namespace app\modules\api\models;

use app\modules\api\components\EstablishRelation;

class FirmOwners extends FirmPeoples
{

    public static $typeInFinder = 1;
    /**
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public function getFirms(){
        return EstablishRelation::hasMany($this,FirmsAsRelation::instance(),['ownerUID'=>'uid']);
    }

}