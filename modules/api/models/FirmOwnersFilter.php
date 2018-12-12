<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 15:34
 */

namespace app\modules\api\models;


use app\modules\api\components\EstablishRelation;

class FirmOwnersFilter extends FirmPeoplesFilter
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