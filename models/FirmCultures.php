<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firmCultures".
 *
 * @property int $id
 * @property string $firmUID
 * @property string $cultureUID
 * @property double $square
 * @property double $weight
 */
class FirmCultures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firmCultures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firmUID', 'cultureUID', 'square', 'weight'], 'required'],
            [['square', 'weight'], 'number'],
            [['firmUID', 'cultureUID'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firmUID' => 'Firm Uid',
            'cultureUID' => 'Culture Uid',
            'square' => 'Square',
            'weight' => 'Weight',
        ];
    }
}
