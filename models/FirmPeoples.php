<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firmPeoples".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $type
 */
class FirmPeoples extends ActiveRecord
{

    public static $typeInFinder;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firmPeoples';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'name', 'phone', 'email'], 'string', 'max' => 250],
            [['email'],'email'],
            [['uid'], 'unique'],
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
            'phone' => 'Phone',
            'email' => 'Email',
            'type' => 'Type',
        ];
    }

    public function beforeSave($insert)
    {
        $this->type = static::$typeInFinder;
        return parent::beforeSave($insert);
    }

    public static function find()
    {
        $query = parent::find();
        $query->where(['type'=>static::$typeInFinder]);
        return $query;
    }
}
