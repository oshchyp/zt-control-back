<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.01.2019
 * Time: 09:33
 */

namespace app\models\firms;


use app\components\models\ModelAsResourceInterface;

class FirmsByElevatorsAsSave extends FirmsAsSave implements ModelAsResourceInterface
{

    use FirmsByElevatorsTrait;

    public function rules()
    {
        return array_merge(parent::rules(),[['elevatorBit','elevatorBitValidate']]);
    }

    public function elevatorBitValidate(){
        $elevatorBitUser = \Yii::$app->user->identity ? \Yii::$app->user->identity->elevatorBit : 1;
        if ($elevatorBitUser & $this->elevatorBit){
            return true;
        }
        $this->addError('elevatorBit', 'it is forbidden to install this elevator to the firm');
        return false;
    }
    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}