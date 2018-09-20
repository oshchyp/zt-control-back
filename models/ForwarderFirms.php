<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "forwarderFirms".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 */
class ForwarderFirms extends ActiveRecord
{

    public static $allInstances = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forwarderFirms';
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
