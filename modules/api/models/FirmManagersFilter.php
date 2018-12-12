<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 15:32
 */

namespace app\modules\api\models;


use app\components\models\EstablishRelation;
use app\models\asrelation\FirmsAsRelation;

class FirmManagersFilter extends FirmPeoplesFilter
{

    public static $typeInFinder = 2;
    /**
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public function getFirms()
    {
        return EstablishRelation::hasMany($this,FirmsAsRelation::instance(),['managerUID'=>'uid']);
    }
}