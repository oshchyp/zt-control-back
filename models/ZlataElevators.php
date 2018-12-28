<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zlataElevator".
 *
 * @property int $id
 * @property string $name
 * @property int $bit
 */
class ZlataElevators extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zlataElevators';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bit'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['bit'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'bit' => 'Bit',
        ];
    }
}
