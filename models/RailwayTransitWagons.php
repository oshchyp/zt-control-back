<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 30.08.2018
 * Time: 17:29
 */

namespace app\models;


use yii\base\Model;

class RailwayTransitWagons extends Model
{

    public $uid;

    public $wagonNumber;

    public $weightLoading;

    public $weightShipping;

    private  $_instanceRailwayTransit;

    private  $_newRecord = false;

    public function rules (){
        return [
            [['uid','weightShipping', 'weightLoading' , 'wagonNumber'], 'safe'],
        ];
    }


    public function getInstanceRT(){
        if (!$this->_instanceRailwayTransit) {
            $instance = $this->uid && !$this->_newRecord ? RailwayTransit::findByUID($this->uid) : null;
            $this->_instanceRailwayTransit = $instance ? $instance : new RailwayTransit();
        }
        return $this->_instanceRailwayTransit;
    }

    public function setAttributesInstanceRT(){
        $instance = $this->getInstanceRT();
        $instance->attributes = $this->toArray();
        return $this;
    }

    public function setNewRecord(){
        $this->_newRecord = true;
        return $this;
    }

    public function setUpdateRecord(){
        $this->_newRecord = false;
        return $this;
    }

    public static function getInstance($data=[]){
        $instance = new static();
        $instance->attributes = $data;
        return $instance;
    }

    public static function getInstanceRTbyData($data=[],$newRecord=false){
        $instance = static::getInstance($data);
        if ($newRecord) {
            $instance->setNewRecord();
        }
        $instance->setAttributesInstanceRT();
        return $instance->getInstanceRT();
    }

}