<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 12.01.2019
 * Time: 13:21
 */

namespace app\models\zlataElevators;


use app\models\ZlataElevators;
use yii\base\Component;

class ZlataElevatorFinder extends Component
{

    public static $allElevators;

    /**
     * @return \app\models\ZlataElevators[]
     */
    public static function getAllElevators(){
        if (static::$allElevators===null){
            static::$allElevators = static::find()->all();
        }
        return static::$allElevators;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find(){
        return ZlataElevators::find();
    }

    /**
     * @param $id
     * @return int
     */
    public static function findBitByID($id){
        $elevator = static::find()->where(['id'=>$id])->one();
        if ($elevator){
            return $elevator->bit;
        }
        return 1;
    }


    /**
     * @param $bit
     * @param bool $first
     * @return ZlataElevators[]|array
     */
    public static function findElevatorsByBit($bit,$first=false){
        $result = [];
        if (static::getAllElevators()){
            foreach (static::getAllElevators() as $elevator){
                if ($bit & $elevator->bit){
                    if ($first){
                        return $elevator;
                    }
                    $result[] = $elevator;
                }
            }
        }
        return $result;
    }

}