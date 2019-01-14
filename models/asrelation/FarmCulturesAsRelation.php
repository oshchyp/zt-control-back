<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:18
 */

namespace app\models\asrelation;

use app\models\FarmCultures;
use app\components\models\EstablishRelation;
use app\components\models\ModelAsRelationInterface;

/**
 * @property \app\models\RegionCultures[] regionCultureRelation
 * @property \app\models\RegionCultures regionCulture
 * @property mixed weightForecast
 * @property mixed yieldForecast
 * @property float yield
 * @property float $yieldLastYear [float]
 * @property float $yieldForecastInside [float]
 */
class FarmCulturesAsRelation extends FarmCultures implements ModelAsRelationInterface
{


    public function fields()
    {
        return array_merge(parent::fields(),['culture', 'yield', 'weightForecast','yieldForecast','balance']);
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return ['culture','regionCultureRelation'];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public function getCulture(){
        return EstablishRelation::hasOne($this,CulturesAsRelation::instance(),['uid' => 'cultureUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionCultureRelation(){
        return EstablishRelation::hasMany($this,RegionCulturesAsRelation::instance(),['regionUID' => 'regionUID']) -> viaTable('farms',['uid'=>'farmUID']);
    }

    /**
     * @return \app\models\RegionCultures|null
     */
    public function getRegionCulture(){
        if ($this->regionCultureRelation){
            foreach ($this->regionCultureRelation as $item){
                if ($item->cultureUID == $this->cultureUID){
                    return $item;
                }
            }
        }
        return null;
    }

    /**
     * @return float|int
     */
    public function getYieldForecast(){
        if ($this->yieldForecastInside){
            return $this->yieldForecastInside;
        } elseif ($this -> regionCulture && $this -> regionCulture->weight){
            return $this -> regionCulture->weight;
        } elseif ($this->yieldLastYear){
            return $this->yieldLastYear;
        } else {
            return 0;
        }
        return  0;
    }

    /**
     * @return float|int
     */
    public function getWeightForecast(){
        return round($this->square * $this->yieldForecast,2);
    }

    /**
     * @return mixed
     */
    public function getBalance(){
        return round($this->weightForecast - $this->weight,2);
    }

    /**
     * @return float|int
     */
    public function getYield(){
        return round($this->weight / $this->square,2);
    }



}