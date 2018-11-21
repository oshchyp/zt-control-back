<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 14:12
 */

namespace app\modules\api\models;


use yii\base\Model;

class RegionCulturesMultiSave extends Model
{

    /**
     * @param array $data
     * @return $this
     */
    public function save($data=[]){
        if ($data && is_array($data)){
            foreach ($data as $item){
                $instance = RegionCulturesFinder::findOneOrCreate($item);
                $instance->attributes = $item;
                $instance->save();
            }
        }
        return $this;
    }

}