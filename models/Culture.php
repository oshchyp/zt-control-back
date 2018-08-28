<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "culture".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 */
class Culture extends \yii\db\ActiveRecord
{
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
}
