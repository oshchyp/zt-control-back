<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 14:05
 */

namespace app\modules\api\models\interfaces;


use yii\db\ActiveRecordInterface;

interface ModelAsResource extends ActiveRecordInterface
{

    /**
     * @return array
     */
    public static function relations();

    /**
     * @return mixed
     */
    public function getRestValidators();

}