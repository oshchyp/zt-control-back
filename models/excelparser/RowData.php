<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 30.10.2018
 * Time: 11:20
 */

namespace app\models\excelparser;


use yii\base\Model;


class RowData extends Model
{

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (key_exists($name,$this->attributes()) && !method_exists($this, $getter)){
            return $this->attributes()[$name];
        }
        return parent::__get($name);
    }

    /**
     * @param array $array
     * @return RowData
     */
    public static function createObject(array $array=[]){
        return new static(['attributes' => $array]);
    }

}