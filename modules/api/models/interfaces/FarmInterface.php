<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:39
 */

namespace app\modules\api\models\interfaces;


use yii\db\ActiveRecordInterface;

interface FarmInterface extends ActiveRecordInterface
{

    /**
     * @return string
     */
    public function getUID();

    /**
     * Adds a new error to the specified attribute.
     * @param string $attribute attribute name
     * @param string $error new error message
     */
    public function addError($attribute, $error = '');

}