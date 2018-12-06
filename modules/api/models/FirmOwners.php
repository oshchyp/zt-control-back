<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 11:22
 */

namespace app\modules\api\models;


use app\models\behaviors\EstablishUID;
use app\models\behaviors\PhoneHandling;
use app\modules\api\components\EstablishRelation;
use app\modules\api\models\interfaces\ModelAsResource;

class FirmOwners extends \app\models\FirmOwners implements ModelAsResource
{


    public function fields()
    {
        return array_merge(parent::fields(),['firms']);
    }

    public function behaviors()
    {
        return [
            'establishUID' => [
                'class' => EstablishUID::className()
            ],
            'phoneHandling' => [
                'class' => PhoneHandling::className()
            ]
        ];
    }

    /**
     * @return array
     */
    public static function relations()
    {
      return ['firms'];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     */
    public function getFirms(){
        return EstablishRelation::hasMany($this,FirmsAsRelation::instance(),['ownerUID'=>'uid']);
    }

}