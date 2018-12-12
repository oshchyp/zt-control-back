<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 11:22
 */

namespace app\models\farms;


use yii\db\ActiveRecordInterface;

interface FarmCultureInterface  extends ActiveRecordInterface
{

    /**
     * @param $farmUID
     */
    public function setFarmUID($farmUID);

    /**
     * @return array
     */
    public function getErrors();

}