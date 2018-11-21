<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 09:28
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\Culture;
use app\modules\api\models\interfaces\RegionCulture;
use yii\base\Model;

class RegionYieldBuilder extends Model
{


    /**
     * @var RegionCulture[]
     */
    protected $regionCulture = [];

    /**
     * @return RegionCulture[]
     */
    public function getRegionCulture()
    {
        return $this->regionCulture;
    }

    /**
     * @param RegionCulture $regionCulture
     */
    public function addRegionCulture (RegionCulture $regionCulture){
        $this->regionCulture[$regionCulture->getCultureUID()] = $regionCulture;
    }

    public function regionYieldLoad(Culture $culture, \app\modules\api\models\abstracts\RegionYield $regionYield){
        $regionYield->setCulture($culture)->setCultureUID($culture->getUID());
        if (isset($this->regionCulture[$culture->getUID()])){
            $regionYield->setWeight($this->regionCulture[$culture->getUID()]->getWeight());
        }
    }



}