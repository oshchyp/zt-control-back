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


    public static $allInstances = null;

    public $addInstanceAfterSave = false;

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
            [['firmUID', 'cultureUID', 'square', 'weight','year'], 'required'],
            [['square', 'weight', 'year'], 'number'],
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

    public static function viewFields(){
        return ['cultureUID', 'square', 'weight','year','culture'];
    }

    public function getCulture (){
        return $this->hasOne(Culture::className(),['uid'=>'cultureUID']);
    }

}
