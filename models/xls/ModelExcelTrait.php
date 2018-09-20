<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 11.09.2018
 * Time: 11:36
 */

namespace app\models\xls;


use PhpOffice\PhpSpreadsheet\Shared\Date;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

trait ModelExcelTrait
{

    /**
     * @var
     */
    public static $logPath;

    /**
     * @var
     */
    public $loadInfo;

    /**
     * @var
     */
    public $modelInstance;

    /**
     * @return array
     */
    public static function excelRules()
    {
        return [
            'A'=>'',
            'B'=>'',
            'C'=>'',
            'D'=>'',
            'E'=>'',
            'F'=>'',
            'G'=>'',
            'H'=>'',
            'I'=>'',
            'J'=>'',
            'K'=>'',
            'L'=>'',
            'M'=>'',
            'N'=>'',
            'O'=>'',
            'P'=>'',
            'Q'=>'',
            'R'=>'',
            'S'=>'',
            'T'=>'',
            'U'=>'',
            'V'=>'',
            'W'=>'',
            'X'=>'',
            'Y'=>'',
            'Z'=>'',
            'AA'=>'',
            'AB'=>'',
            'AC'=>'',
            'AD'=>'',
            'AE'=>'',
            'AF'=>'',
            'AG'=>'',
            'AH'=>'',
            'AI'=>'',
            'AJ'=>'',
            'AK' => '',
            'AL' => '',
            'AM' => '',
            'AN' => '',
            'AO' => '',
            'AP' => '',
            'AQ' => '',
            'AR' => '',
            'AS' => '',
            'AT' => '',
            'AU' => '',
            'AV' => '',
            'AW'=>'',
            'AX'=>'',
            'AY'=>'',
            'AZ'=>'',
        ];
    }

    /**
     * @param $loadInfo
     * @return ModelExcelTrait
     */
    public static function excelDataProcessingInstance($loadInfo){
        $instance = new static();
        $instance->attributes = $loadInfo;
        return $instance;
    }

    /**
     * @param $loadInfo\
     */
    public function setLoadInfo($loadInfo){
        $this->loadInfo = $loadInfo;
    }


    /**
     * @return mixed
     */
    public function getModelInstance(){
         return $this->modelInstance;
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function getLoadInfo($key=null){
        if ($key !== null){
            return ArrayHelper::getValue($this->loadInfo,$key,null);
        }
        return $this->loadInfo;
    }

    /**
     * @param $key
     * @return null|string
     */
    public function getLoadInfoValue($key){
        $object = $this->getLoadInfo($key);
        if (is_object($object)){
            return trim($object->getFormattedValue());
        }
        return null;
    }

    /**
     * @return array
     */
    public function getLoadInfoValues(){
        $result = [];
        if ($loadInfo = $this->getLoadInfo()){
            foreach ($loadInfo as $k=>$v){
                $result[$k]=$this->getLoadInfoValue($k);
            }
        }
        return $result;
    }

    /**
     * @param $key
     * @return int
     * @throws \Exception
     */
    public function getDateTimestamp($key){
         $value = $this->getLoadInfo($key);
         if ($value && is_object($value)){
             $value = $value->getFormattedValue();
             return is_numeric($value) ? Date::excelToTimestamp($value) : 0;
         }
         return 0;
    }

    /**
     * @return $this
     */
    public function addModelToAllInstances(){
        $instances = static::getAllModelInstances();
        $instances[] = clone $this->getModelInstance();
        static::$allModelInstances = $instances;
        return $this;
    }

    /**
     * @param $className
     * @param $object
     */
    public static function addModelToAllAuxInstances($className,$object){
        $instances = static::getAllAuxInstances($className);
        $instances[] = clone $object;
        static::$auxInstances[$className] = $instances;
    }

    /**
     * @return $this
     */
    public function saveModel(){
        $new = $this->getModelInstance()->getIsNewRecord();
        $this->getModelInstance()->save();
        if ($new)
            $this->addModelToAllInstances();
        return $this;
    }

    /**
     * @param $attrValue
     * @param $attrName
     * @param $instances
     * @return null
     */
    public static function findInstanceByAttrValue($attrValue,$attrName,$instances){
        if ($instances){
            foreach ($instances as $object){
                if ($object->$attrName == $attrValue){
                    return $object;
                }
            }
        }
        return null;
    }

    /**
     * @param $attrValue
     * @param $attrName
     * @param $className
     * @return null
     */
    public static function getAuxInstanceByAttrValue($attrValue,$attrName,$className){
        $instance = static::findInstanceByAttrValue($attrValue,$attrName,static::getAllAuxInstances($className));
        return $instance ? $instance : new $className;
    }

    /**
     * @param $value
     * @param $attr
     * @param $class
     * @param array $data
     * @return null
     */
    public static function getAuxInstanceByAttrValueOrSave($value,$attr,$class,$data=[]){
        $instance = static::getAuxInstanceByAttrValue($value,$attr,$class);
        if ($instance->getIsNewRecord()){
            $instance->setUid();
            $instance->$attr = $value;
            $instance->attributes = $data;
            $instance->save();
            static::addModelToAllAuxInstances($class,$instance);
        }
        return $instance;
    }

    /**
     * @return mixed
     */
    public static function getAllModelInstances(){
        if (static::$allModelInstances === null){
            $modelClassName = static::$modelClassName;
            static::$allModelInstances = $modelClassName::find()->all();
        }
        return static::$allModelInstances;
    }

    /**
     * @return mixed
     * @throws \yii\base\Exception
     */
    public static function getLogPath(){
        if (!static::$logPath){
            static::$logPath = \Yii::getAlias('@app/logs/').date('Y-m-d H:i');
            FileHelper::createDirectory(static::$logPath);
        }
        return static::$logPath;
    }

    /**
     * @param $className
     * @return mixed
     */
    public static function getAllAuxInstances($className){
        if (!is_array(static::$auxInstances) || !key_exists($className,static::$auxInstances)){
            static::$auxInstances[$className] = $className::find()->all();
           // file_put_contents(static::getLogPath().'/find_'.uniqid().'_'.basename(str_replace('\\','/',$className)).'.txt','');
        }
        return static::$auxInstances[$className];
    }


}