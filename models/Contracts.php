<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contracts".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $firmUID
 * @property string $cultureUID
 * @property string $regionUID
 * @property string $receiverPointUID
 * @property double $amount
 */
class Contracts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contracts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'name', 'firmUID', 'cultureUID', 'regionUID', 'receiverPointUID', 'amount'], 'required'],
            [['uid'], 'unique'],
            [['amount'], 'number'],
            [['uid', 'name', 'firmUID', 'cultureUID', 'regionUID', 'receiverPointUID'], 'string', 'max' => 250],
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
            'firmUID' => 'Firm Uid',
            'cultureUID' => 'Culture Uid',
            'regionUID' => 'Region Uid',
            'receiverPointUID' => 'Receiver Point Uid',
            'amount' => 'Amount',
        ];
    }
}
