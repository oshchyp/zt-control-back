<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 09:39
 */

namespace app\models\farms;


use app\components\models\EstablishRelation;
use app\components\models\ModelAsResourceInterface;
use app\models\asrelation\FarmCulturesAsRelation;
use app\models\asrelation\FirmsAsRelation;
use app\models\asrelation\PointsAsRelations;
use app\models\asrelation\RegionsAsRelation;

class Farms extends \app\models\Farms implements ModelAsResourceInterface, FarmInterface
{

    /**
     * @return array
     */
    public function fields()
    {
        return array_merge(parent::fields(),['firm','region','point','cultures']);
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return ['firm','region','point','cultures'];
    }

    /**
     * @return string
     */
    public function getUID()
    {
        return $this->uid;
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public function getFirm (){
        return EstablishRelation::hasOne($this,FirmsAsRelation::instance(),['uid'=>'firmUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public function getRegion (){
        return EstablishRelation::hasOne($this,RegionsAsRelation::instance(),['uid'=>'regionUID']);
    }


    /**
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public function getPoint (){
        return EstablishRelation::hasOne($this,PointsAsRelations::instance(),['uid'=>'pointUID']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCultures(){
        return EstablishRelation::hasMany($this,FarmCulturesAsRelation::instance(),['farmUID'=>'uid']);
    }

}