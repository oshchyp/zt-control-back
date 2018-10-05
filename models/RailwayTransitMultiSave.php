<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.08.2018
 * Time: 10:44
 */

namespace app\models;



use yii\base\Model;

class RailwayTransitMultiSave extends model
{

    public $wagons;

    public $generalInfo;

    private $_savedData = [];


    public function rules(){
        return [
            [['wagons','generalInfo'], 'safe']
        ];
    }

    public function save($newRecord=false){
        $this->_savedData = [];
        if (!$this->wagons || !is_array($this->wagons)){
//            $this->addError('wagons','Wagons are absent');
//            return;
            $this->wagons[] = [];
        }
        foreach ($this->wagons as $wagonInfo){
            $instance = RailwayTransitWagons::getInstanceRTbyData($wagonInfo,$newRecord);
            $instance->attributes = $this->generalInfo;
//            dump($this->generalInfo);
//          //  dump($instance->forwarderFirmName);
//            dump($instance->attributes,1);
            $instance->save();
            if ($instance->hasErrors()){
                $this->addErrors(['wagon '.$instance->getWagonName() => $instance->getErrors()]);
            }
            $this->_savedData[] = $instance;
         }
    }

    public function create(){
        $this->save(true);
    }

    public function update(){
        $this->save(false);
    }

    public static function getInstance($data=[]){
        $instance = new static();
        $instance->attributes = $data;
        return $instance;
    }

}