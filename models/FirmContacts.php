<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firmContacts".
 *
 * @property int $id
 * @property string $contactUID
 * @property string $firmUID
 */
class FirmContacts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firmContacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contactUID', 'firmUID'], 'required'],
            [['contactUID', 'firmUID'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contactUID' => 'Contact Uid',
            'firmUID' => 'Firm Uid',
        ];
    }
}
