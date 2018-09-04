<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "elevators".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 */
class Elevators extends ActiveRecord
{

    public static $allInstances = null;

    public $addInstanceAfterSave = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'elevators';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'name'], 'required'],
            [['uid', 'name'], 'string', 'max' => 250],
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
        ];
    }
}
