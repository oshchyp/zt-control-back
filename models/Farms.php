<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "farms".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $regionUID
 * @property string $pointUID
 * @property string $square
 * @property string $firmUID [varchar(250)]
 */
class Farms extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'farms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['square'], 'number'],
            [['uid', 'name', 'regionUID', 'pointUID', 'firmUID'], 'string', 'max' => 250],
            [['uid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'name' => 'Name',
            'regionUID' => 'Region Uid',
            'pointUID' => 'Point Uid',
            'square' => 'Square',
            'firmUID' => 'Firm UID'
        ];
    }
}
