<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.01.2019
 * Time: 09:33
 */

namespace app\models\firms;


use app\components\bitAccess\BitAccessBehavior;
use app\components\models\ModelAsResourceInterface;
use app\models\ZlataElevators;

class FirmsByElevatorsAsSave extends FirmsAsSave implements ModelAsResourceInterface
{

    public function rules()
    {
        return array_merge(parent::rules(),[
            ['elevatorBit', 'required'],
            ['elevatorID','safe'],
             ]);
    }


    public function behaviors()
    {
        return [
            [
                'class'=> BitAccessBehavior::className(),
                'userBit' => \Yii::$app->user->identity ? \Yii::$app->user->identity->elevatorBit : null,
                'errorForbidden' => 'You are not allowed to edit this firm.',
                'errorAllowed' => 'You are not allowed to choose this elevator for firms.'
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getResourceBitOld(){
        return $this->getOldAttribute('elevatorBit');
    }

    /**
     * @return mixed
     */
    public function getResourceBitNew(){
        return $this->getAttribute('elevatorBit');
    }

    public function setElevatorID($elevatorID){
        $elevatorObject = ZlataElevators::find()->where(['id'=>$elevatorID])->one();
        if ($elevatorObject){
            $this->setAttribute('elevatorBit',$elevatorObject->bit);
        }
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}