<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "farmCultures".
 *
 * @property int $id
 * @property string $farmUID
 * @property string $cultureUID
 * @property float $square
 * @property int $year [int(11)]
 * @property string $weight [decimal(5,2)]
 */
class FarmCultures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'farmCultures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year'],'integer'],
            [['square', 'weight'], 'number'],
            [['farmUID', 'cultureUID'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'farmUID' => 'Farm Uid',
            'cultureUID' => 'Culture Uid',
            'square' => 'Square',
            'yield' => 'Yield',
            'year' => 'Year'
        ];
    }
}
