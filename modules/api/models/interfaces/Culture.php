<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 09:41
 */

namespace app\modules\api\models\interfaces;


interface Culture
{

    /**
     * @return string
     */
    public function getUID();

    /**
     * @return string
     */
    public function getName();

}