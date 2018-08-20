<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "regions".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regions';
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

    public static function viewFields(){
        return ['id','uid','name','points'];
    }

    public static function findByName($name)
    {
        return static::find()->where(['name' => $name])->one();
    }

    public function getPoints()
    {
        return $this->hasMany(Points::className(), ['regionUID' => 'uid']);
    }
}
