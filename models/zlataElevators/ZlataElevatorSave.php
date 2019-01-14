<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 14.01.2019
 * Time: 10:54
 */

namespace app\models\zlataElevators;


use app\models\ZlataElevators;
use yii\base\Component;
use yii\base\Model;

class ZlataElevatorSave extends Component
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }


    /**
     * @param $id
     * @param $attribute
     */
    public function setElevatorsByID($id,$attribute){
        if ($elevator = ZlataElevators::find()->where(['id'=>$id])->one()){
            $this->getModel()->$attribute = (int)$elevator->bit;
        }
    }

    /**
     * @param $elevatorsIDArray
     * @param $attribute
     */
    public function setElevatorsByIDArray($elevatorsIDArray, $attribute){
        $elevatorBit = (int)ZlataElevators::find()->where(['id'=>$elevatorsIDArray])->sum('bit');
        if (!$elevatorBit){
            $elevatorBit = 1;
        }
        $this->getModel()->$attribute = $elevatorBit;
    }


    /**
     * @param Model $model
     * @return ZlataElevatorSave
     */
    public static function getInstance (Model $model){
        return new static(['model' => $model]);
    }

}