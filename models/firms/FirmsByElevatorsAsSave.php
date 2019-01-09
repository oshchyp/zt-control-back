<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.01.2019
 * Time: 09:33
 */

namespace app\models\firms;


use app\components\models\ModelAsResourceInterface;
use app\models\ZlataElevators;

class FirmsByElevatorsAsSave extends FirmsAsSave implements ModelAsResourceInterface
{

    use FirmsByElevatorsTrait;

    public $elevatorID;

    public function rules()
    {
        return array_merge(parent::rules(),[
            ['elevatorID', 'elevatorIDValidate'],
            ['elevatorBit','elevatorBitValidate'],

            ]);
    }


    public function elevatorIDValidate(){
        $elevatorObject = ZlataElevators::find()->where(['id'=>$this->elevatorID])->one();
        if ($elevatorObject){
            $this->setAttribute('elevatorBit',$elevatorObject->bit);
        } else {
            $this->addError('elevatorBit', 'elevator dose not exist!');
        }
    }

    /**
     * @return bool
     */
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