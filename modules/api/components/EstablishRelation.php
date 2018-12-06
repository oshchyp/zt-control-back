<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 15:24
 */

namespace app\modules\api\components;


use app\modules\api\models\interfaces\ModelAsRelation;
use yii\base\Component;
use yii\db\ActiveRecord;

class EstablishRelation extends Component
{

    /**
     * @param ActiveRecord $activeRecordModel
     * @param ModelAsRelation $relationClassName
     * @param array $condition
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public static function hasOne(ActiveRecord $activeRecordModel,ModelAsRelation $relationClassName,$condition=[]){
        self::isModelAsRelation($relationClassName);
        return $activeRecordModel->hasOne($relationClassName,$condition)->with($relationClassName::relations());
    }

    /**
     * @param ActiveRecord $activeRecordModel
     * @param ModelAsRelation $relationClassName
     * @param array $condition
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public static function hasMany(ActiveRecord $activeRecordModel, ModelAsRelation $relationClassName,$condition=[]){
        self::isModelAsRelation($relationClassName);
        return $activeRecordModel->hasMany($relationClassName,$condition)->with($relationClassName::relations());
    }

    /**
     * @param $relationClassName
     * @throws \Exception
     */
    public static function isModelAsRelation($relationClassName){
    //    $ar = $relationClassName instanceof 'app\modules\api\models\interfaces\ModelAsRelation';
      //  dump($relationClassName instanceof 'app\\modules\\api\\models\\interfaces\\ModelAsRelation');
        if (!is_a($relationClassName,'app\\modules\\api\\models\\interfaces\\ModelAsRelation')){
            throw new \Exception('relation class is not valid');
        }
    }

}