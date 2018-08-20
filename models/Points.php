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
class Points extends \yii\db\ActiveRecord
{
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
            [['uid', 'name', 'regionUID'], 'required'],
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
