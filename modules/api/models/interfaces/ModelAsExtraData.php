<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 15:07
 */

namespace app\modules\api\models\interfaces;


use yii\db\ActiveRecordInterface;

interface ModelAsExtraData extends ActiveRecordInterface
{

    /**
     * @return array
     */
    public static function relations();
}