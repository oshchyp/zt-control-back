<?php

namespace app\models;

use app\models\helper\Main;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "culture".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int $classesExist
 */
class Culture extends ActiveRecord
{

    public static $allInstances = null;

    public $addInstanceAfterSave = false;


    /**
     * @var
     */
    public $test;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'culture';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'name'], 'required'],
            [['uid', 'name'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'name' => 'Name',
        ];
    }

    /**
     * @return array
     */
    public function fields()
    {

       return ['id','uid','name','classes'];
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function getInstanceByName($name){
        return parent::getInstanceByAttrValue($name,'name');
    }

    /**
     * @param $name
     * @return mixed
     *
     */
    public static function getInstanceByNameOrSave($name){
        $instance = static::getInstanceByName($name);
        $instance->setEventsParsing();
        if ($instance->getIsNewRecord()){
            $instance->name = $name;
            $instance->save();
        }
        return $instance;
    }

    /**
     * @return array|null
     */
    public function getClasses(){
        return $this->classesExist ? RailwayTransit::classes() : null;
    }
}
