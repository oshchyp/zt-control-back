<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.01.2019
 * Time: 13:19
 */

namespace app\models\firmManagers;


use app\components\models\EstablishRelation;
use app\models\asrelation\FirmsAsRelation;

trait FirmManagersRelations
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirms()
    {
        return EstablishRelation::hasMany($this,FirmsAsRelation::instance(),['managerUID'=>'uid']);
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return ['firms'];
    }
}