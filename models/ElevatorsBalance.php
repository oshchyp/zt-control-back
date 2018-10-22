<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "elevatorsBalance".
 *
 * @property int $id
 * @property string $elevatorUID
 * @property string $cultureUID
 * @property string $balanceDeposited
 * @property string $balanceZlata
 * @property string $debit
 * @property string $credit
 */
class ElevatorsBalance extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'elevatorsBalance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['elevatorUID'], 'required'],
            [['balanceDeposited', 'balanceZlata', 'debit', 'credit'], 'number'],
            [['elevatorUID', 'cultureUID'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'elevatorUID' => 'Elevator Uid',
            'cultureUID' => 'Culture Uid',
            'balanceDeposited' => 'Balance Deposited',
            'balanceZlata' => 'Balance Zlata',
            'debit' => 'Debit',
            'credit' => 'Credit',
        ];
    }

    public function fields()
    {
        return [ 'balanceDeposited',  'balanceZlata', 'debit', 'credit','cultureUID','culture'];
    }

    public function getCulture(){
        return $this->hasOne(Culture::className(),['uid'=>'cultureUID']);
    }

}
