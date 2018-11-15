<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.10.2018
 * Time: 11:08
 */

namespace app\models;


use yii\base\Model;

class DateSet extends Model
{

    /**
     * @var Model
     */
    public $model;

    /**
     * @var array
     */
    public $dateAttributes=[];

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var string
     */
    private $_format = 'd.m.Y';

    public function rules()
    {
        return [
            ['model','modelValidate'],
            ['dateAttributes','dateAttributesValidate'],
            ['data','dataValidate'],
        ];
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->_format = $format;
    }

    /**
     * @return bool
     */
    public function modelValidate(){
        return true;
    }

    /**
     * @return bool
     */
    public function dateAttributesValidate(){
        return true;
    }

    /**
     * @return bool
     */
    public function dataValidate(){
        return true;
    }

    /**
     * @param string $value
     * @return int
     */
    public function getTimestamp($value){
        return \DateTime::createFromFormat($this->getFormat(), $value)->getTimestamp();
    }

    /**
     * @param int $value
     * @return string
     */
    public function getDate($value){
        $dateObject = new \DateTime();
        $dateObject->setTimestamp($value);
        return $dateObject->format($this->getFormat());
    }

    /**
     * @param $attr
     * @param $value
     */
    public function setTimestampAttr($attr,$value){
        $this->model->$attr = $this->getTimestamp($value);
    }

    /**
     * @param $attr
     * @param $value
     */
    public function setDateAttr($attr,$value){
        $this->model->$attr = $this->getDate($value);
    }

    /**
     *
     */
    public function setTimestampToAttributes(){
        if ($this->validate()){
            $this->setToAttributes('setTimestampAttr');
        }
    }

    public function setDateToAttributes(){
        $this->setToAttributes('setDateAttr');
    }

    /**
     * @param $callable
     */
    public function setToAttributes($callable){
        foreach ($this->dateAttributes as $attr){
            if (isset($this->data[$attr]) && is_string($this->data[$attr])){
                call_user_func([$this,$callable],$attr,$this->data[$attr]);
            }
        }
    }

    /**
     * @param Model $model
     * @param $dateAttributes
     * @param $data
     */
    public static function setTimestamp(Model $model, $dateAttributes, $data){
        $instance = new static([
            'model' => $model,
            'dateAttributes' => $dateAttributes,
            'data' => $data
        ]);
        $instance->setTimestampToAttributes();
    }

    /**
     * @param Model $model
     * @param $dateAttributes
     * @param $data
     */
    public static function setDate(Model $model, $dateAttributes, $data){
        $instance = new static([
            'model' => $model,
            'dateAttributes' => $dateAttributes,
            'data' => $data
        ]);
        $instance->setDateToAttributes();
    }

}