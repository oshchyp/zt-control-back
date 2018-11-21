<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 19.11.2018
 * Time: 14:17
 */

namespace app\models\interfaces;


interface IFirmCultures
{
    /**
     * @return string
     */
    public function getCultureUID();

    /**
     * @return float
     */
    public function getSquare();

    /**
     * @return float
     */
    public function getWeight();

    /**
     * @return integer
     */
    public function getYear();

    /**
     * @return Culture
     */
    public function cultureInfo();
}