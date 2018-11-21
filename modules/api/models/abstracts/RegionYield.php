<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 09:32
 */

namespace app\modules\api\models\abstracts;


abstract class RegionYield
{

    public $cultureUID;

    public $regionUID;

    public $weight;

    public $culture;

    /**
     * @return mixed
     */
    public function getCultureUID()
    {
        return $this->cultureUID;
    }

    /**
     * @param $cultureUID
     * @return $this
     */
    public function setCultureUID($cultureUID)
    {
        $this->cultureUID = $cultureUID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegionUID()
    {
        return $this->regionUID;
    }

    /**
     * @param $regionUID
     * @return $this
     */
    public function setRegionUID($regionUID)
    {
        $this->regionUID = $regionUID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * @param $culture
     * @return $this
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;
        return $this;
    }



}