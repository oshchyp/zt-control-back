<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 10:27
 */

namespace app\modules\api\models;

use app\modules\api\models\interfaces\ModelAsResource;

/**
 * Class Regions
 * @package app\modules\api\models
 * @property \app\models\RegionCultures[] $cultures
 */
class Regions extends \app\models\Regions implements ModelAsResource
{

    public static $allCultures;

    public function fields()
    {
        return array_merge(parent::fields(),['points']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCultures(){
        return $this->hasMany(RegionCultures::className(), ['regionUID' => 'uid']);
    }

    public function getCulturesTable(){
        if (static::$allCultures && is_array(static::$allCultures)){
            $result = [];
            $regionYieldBuilder = new RegionYieldBuilder();
            if ($this->cultures)
            foreach ($this->cultures as $item){
                $regionYieldBuilder->addRegionCulture($item);
            }
            foreach (static::$allCultures as $item){
                $regionYield = new RegionYield();
                $regionYield->setRegionUID($this->uid);
                $regionYieldBuilder->regionYieldLoad($item,$regionYield);
                $result[] = $regionYield;
            }
            return $result;
        }
        return [];
    }

}