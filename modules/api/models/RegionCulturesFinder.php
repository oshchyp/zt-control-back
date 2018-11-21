<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 13:56
 */

namespace app\modules\api\models;


use yii\base\Model;

class RegionCulturesFinder extends Model
{

    public $cultureUID;

    public $regionUID;

    public function rules()
    {
        return [
            [['cultureUID','regionUID'],'string']
        ];
    }


    /**
     * @param array $data
     * @return RegionCulturesFinder
     */
    public static function getInstance($data=[]){
        $inst = new static();
        $inst->attributes = $data;
        return $inst;
    }

    /**
     * @param $data
     * @return \app\models\RegionCultures|array|null|\yii\db\ActiveRecord
     */
    public static function findOne($data){
        $inst = static::getInstance($data);
        return \app\models\RegionCultures::find()->where(['cultureUID'=>$inst->cultureUID,'regionUID'=>$inst->regionUID])->one();
    }

    /**
     * @param $data
     * @return \app\models\RegionCultures|array|null|\yii\db\ActiveRecord
     */
    public static function findOneOrCreate($data){
        $model = static::findOne($data);
        return $model ? $model : new \app\models\RegionCultures();
    }

}