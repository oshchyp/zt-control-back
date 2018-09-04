<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.09.2018
 * Time: 11:40
 */

namespace app\models\xls;


use yii\base\Model;

class ModelExcel extends Model
{


    public $loadInfo;

    public function getLoadInfo($key=null){
        $result = $this->loadInfo;
        if ($key !== null){
            $result = isset($result[$key]) ? $result[$key] : null;
        }
        return $result;
    }

    public function setValuesLoadInfo(){
        if (is_array($this->getLoadInfo()) && $this->getLoadInfo()){
            foreach ($this->getLoadInfo() as $attr=>$valueObject){
                if (is_object($valueObject) && property_exists($this,$attr)){
                    $this->$attr = $valueObject->getValue();
                }
            }
        }
        return $this;
    }

    public static function excelDataProcessing($loadInfo)
    {
        //dump($loadInfo,1);
       $object = new static();
       $object -> attributes = $loadInfo;
       $object -> loadInfo = $loadInfo;
       $object -> runObjectDataProcessing();
    }
}