<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firmOwners".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $phone
 * @property string $email
 */
class FirmOwners extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firmOwners';
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
        ];
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}