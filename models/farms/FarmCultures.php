<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 11:24
 */

namespace app\models\farms;


class FarmCultures extends \app\models\FarmCultures implements  FarmCultureInterface
{

    /**
     * @param $farmUID
     */
    public function setFarmUID($farmUID)
    {
        $this->farmUID = $farmUID;
    }
}