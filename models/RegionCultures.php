<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "regionCultures".
 *
 * @property int $id
 * @property string $cultureUID
 * @property string $regionUID
 * @property double $weight
 */
class RegionCultures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regionCultures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weight'], 'number'],
            [['cultureUID', 'regionUID'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cultureUID' => 'Culture Uid',
            'regionUID' => 'Region Uid',
            'weight' => 'Weight',
        ];
    }


    public function fields()
    {
        return array_merge(parent::fields(),['culture']);
    }

    /**
     * @return array
     */
    public static function viewFields(){
        return ['cultureUID', 'weight','culture'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCulture (){
        return $this->hasOne(Culture::className(),['uid'=>'cultureUID']);
    }

    public static function multiSave($data){
        if ($data && is_array($data)){
            foreach ($data as $item){

            }
        }
    }
}
