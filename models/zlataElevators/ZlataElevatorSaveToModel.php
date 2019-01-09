<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.01.2019
 * Time: 09:54
 */

namespace app\models\zlataElevators;


use app\models\ZlataElevators;

trait ZlataElevatorSaveToModel
{

    /**
     * @param array $elevators
     */
    public function setElevators($elevators=[]){
        $elevatorBit = (int)ZlataElevators::find()->where(['id'=>$elevators])->sum('bit');
        if (!$elevatorBit){
            $elevatorBit = 1;
        }
        $this->elevatorBit = $elevatorBit;
    }


    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            ['elevators','safe']
        ]);
    }

}