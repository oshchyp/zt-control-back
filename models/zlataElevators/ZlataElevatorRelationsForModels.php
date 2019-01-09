<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.01.2019
 * Time: 09:53
 */

namespace app\models\zlataElevators;


trait ZlataElevatorRelationsForModels
{
    public static $allElevators;

    /**
     * @return \app\models\ZlataElevators[]
     */
    public function getAllElevators(){
        if (static::$allElevators===null){
            static::$allElevators = \app\models\ZlataElevators::find()->all();
        }
        return static::$allElevators;
    }

    /**
     * @return \app\models\ZlataElevators[]
     */
    public function getElevators(){
        $result = [];
        if ($this->getAllElevators()){
            foreach ($this->getAllElevators() as $elevator){
                if ($this->elevatorBit & $elevator->bit){
                    $result[] = $elevator;
                }
            }
        }
        return $result;
    }

}