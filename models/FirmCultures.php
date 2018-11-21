<?php

namespace app\models;

/**
 * This is the model class for table "firmCultures".
 *
 * @property int $id
 * @property string $firmUID
 * @property string $cultureUID
 * @property int $year
 * @property double $square
 * @property double $weight
 * @property Culture $culture
 * @property RegionCultures regionCulture
 * @property float weightForecast
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
            [['firmUID', 'cultureUID'], 'required'],
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

    public function fields()
    {
        return array_merge(parent::fields(),['culture', 'regionCulture', 'weightForecast', 'yield', 'yieldForecast']);
    }

    /**
     * @return array
     */
    public static function viewFields(){
        return ['cultureUID', 'square', 'weight','weightForecast', 'year','culture','regionCulture','yield', 'yieldForecast'];
    }

    /**
     * @return float|int
     */
    public function getWeightForecast(){
        $regionCulture = $this -> regionCulture;
        return $regionCulture  ? $regionCulture->weight : 0;
    }

    /**
     * @return float|int
     */
    public function getYield(){
        return $this->square * $this->weight;
    }

    /**
     * @return float|int
     */
    public function getYieldForecast(){
        return $this->square * $this->weightForecast;
    }

    public function getCulture (){
        return $this->hasOne(Culture::className(),['uid'=>'cultureUID']);
    }

    public function getRegionCulture(){
        return $this->hasOne(RegionCultures::className(),['regionUID' => 'regionUID']) -> viaTable('firms',['uid'=>'firmUID'])->where(['cultureUID'=>$this->cultureUID]);
    }
}
