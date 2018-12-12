<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 15:24
 */

namespace app\components;


use app\modules\api\models\interfaces\ModelAsRelation;
use yii\base\Component;
use yii\db\ActiveRecord;

class EstablishRelation extends Component
{

    /**
     * @param ActiveRecord $activeRecordModel
     * @param ModelAsRelation $ModelAsRelation
     * @param array $condition
     * @return \yii\db\ActiveQuery
     */
    public static function hasOne(ActiveRecord $activeRecordModel,ModelAsRelation $ModelAsRelation,$condition=[]){
        return $activeRecordModel->hasOne($ModelAsRelation,$condition)->with($ModelAsRelation::relations());
    }

    /**
     * @param ActiveRecord $activeRecordModel
     * @param ModelAsRelation $ModelAsRelation
     * @param array $condition
     * @return \yii\db\ActiveQuery
     */
    public static function hasMany(ActiveRecord $activeRecordModel, ModelAsRelation $ModelAsRelation,$condition=[]){
        return $activeRecordModel->hasMany($ModelAsRelation::className(),$condition)->with($ModelAsRelation::relations());
    }

}