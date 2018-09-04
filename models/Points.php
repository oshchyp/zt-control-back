<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "points".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $regionUID
 */
class Points extends ActiveRecord
{


    public static $allInstances = null;

    public $addInstanceAfterSave = false;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'points';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'name'], 'required'],
            [['uid', 'name', 'regionUID'], 'string', 'max' => 250],
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
            'regionUID' => 'Region Uid',
        ];
    }

    public static function findByName($name){
        return static::find()->where(['name'=>$name])->one();
    }
}
