<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 10:30
 */

namespace app\modules\api\models;


use app\modules\api\models\interfaces\RegionCulture;

class RegionCultures extends \app\models\RegionCultures implements RegionCulture
{



    /**
     * @return string
     */
    public function getCultureUID()
    {
        return $this->cultureUID;
    }

    /**
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }
}