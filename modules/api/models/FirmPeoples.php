<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 12:59
 */

namespace app\modules\api\models;


use app\models\behaviors\EstablishUID;
use app\models\behaviors\PhoneHandling;
use app\modules\api\models\interfaces\ModelAsResource;

abstract class FirmPeoples extends \app\models\FirmPeoples implements ModelAsResource
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
    abstract public function getFirms();
}