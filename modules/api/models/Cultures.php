<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 10:42
 */

namespace app\modules\api\models;


use app\models\Culture;

class Cultures extends Culture implements \app\modules\api\models\interfaces\Culture
{

    /**
     * @return string
     */
    public function getUID()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->uid;
    }
}