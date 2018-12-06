<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 15:04
 */

namespace app\modules\api\models\interfaces;


use yii\db\ActiveRecordInterface;

interface ModelAsRelation extends ActiveRecordInterface
{

    /**
     * @return array
     */
    public static function relations();

}