<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firmDistances".
 *
 * @property int $id
 * @property string $firmUID
 * @property string $pointUID
 * @property double $distance
 */
class FirmDistances extends ActiveRecord
{


    public static $allInstances = null;

    public $addInstanceAfterSave = false;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firmDistances';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firmUID', 'pointUID', 'distance'], 'required'],
            [['distance'], 'number'],
            [['firmUID', 'pointUID'], 'string', 'max' => 250],
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
            'pointUID' => 'Point Uid',
            'distance' => 'Distance',
        ];
    }
}