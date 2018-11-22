<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 12:24
 */

namespace app\modules\api\models;


class Firms extends \app\models\Firms
{

    /**
     * @return array
     */
    public function fields(){
        $fields = ['nearElevator','contacts','cultures','mainCulture','distances','region', 'point','processedSquare','mainContact',
            'sender' => function ($model){
                foreach (static::distributionStatuses() as $item){
                    if ($item['id'] == $model->sender){
                        return $item;
                    }
                }
                return [
                    'name' => 'Не известно',
                    'id' => 0
                ];
            }
        ];
        return array_merge(parent::fields(),$fields);
    }

    /**
     * @return array
     */
    public static function relations(){
        return ['contacts', 'region', 'point','nearElevator',
            'cultures.culture', 'cultures.regionCulture', 'distances.point', 'mainCulture',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistances(){
        return $this->hasMany(FirmsDistances::className(),['firmUID'=>'uid']);
    }

    public function getProcessedSquare(){
        $result = 0;
        if ($this->cultures){
            foreach ($this->cultures as $item){
                if ($item->year==date('Y')){
                    $result += $item->square;
                }
            }
        }
        return $result;
    }

}