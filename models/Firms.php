<?php

namespace app\models;


use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "firms".
 *
 * @property int $id
 * @property string uid
 * @property string $name
 * @property string $rdpu
 * @property FirmCultures[] cultures
 * @property contacts
 * @property float $square [float]
 * @property string $regionUID [varchar(250)]
 * @property string $pointUID [varchar(250)]
 * @property string $nearElevatorUID [varchar(250)]
 * @property bool $sender [tinyint(1)]
 * @property int $test [int(11)]
 * @property string $mainCultureUID [varchar(255)]
 * @property string $ownerUID
 */
class Firms extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'firms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid'], 'unique'],
            [['uid', 'name'], 'required'],
            [['square'], 'number'],
            [['uid', 'name', 'rdpu', 'regionUID', 'pointUID', 'nearElevatorUID', 'mainCultureUID','ownerUID'], 'string', 'max' => 250],
            ['sender', 'in', 'range' => ArrayHelper::getColumn(static::distributionStatuses(), 'id')],
            [['contacts', 'cultures', 'distances'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'director' => 'Director',
            'rdpu' => 'Rdpu',
            'contactUID' => 'Contact Uid',
        ];
    }

    /**
     * @return array
     */
    public static function distributionStatuses()
    {
        return [
            [
                'name' => 'Не известно',
                'id' => 0,
            ],
            [
                'id' => 1,
                'name' => 'Не участвует'
            ],
            [
                'id' => 2,
                'name' => 'Участвует'
            ],
            [
                'id' => 3,
                'name' => 'Добавить в рассылку'
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['uid' => 'regionUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoint()
    {
        return $this->hasOne(Points::className(), ['uid' => 'pointUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNearElevator()
    {
        return $this->hasOne(Elevators::className(), ['uid' => 'nearElevatorUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistances()
    {
        return $this->hasMany(FirmsDistances::className(), ['firmUID' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contacts::className(), ['firmUID' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainContact()
    {
        return $this->hasOne(Contacts::className(), ['firmUID' => 'uid'])->where(['main' => 1]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCultures()
    {
        return $this->hasMany(FirmCultures::className(), ['firmUID' => 'uid'])->orderBy(['year' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainCulture()
    {
        return $this->hasOne(Culture::className(), ['uid' => 'mainCultureUID']);
    }


}
