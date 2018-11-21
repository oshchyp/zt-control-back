<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 09:30
 */

namespace app\modules\api\models\interfaces;


interface RegionCulture
{

    /**
     * @return string
     */
    public function getCultureUID();


    /**
     * @return string
     */
    public function getWeight();

}