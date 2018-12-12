<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 11:22
 */

namespace app\modules\api\models\interfaces;


use Codeception\Lib\Interfaces\ActiveRecord;
use yii\db\ActiveQueryInterface;
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