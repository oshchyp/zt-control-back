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
 */
class Firms extends ActiveRecord
{

    public $saveContacts = null;

    /**
     * @var null
     */
    public $saveCultures = null;

    /**
     * @var null
     */
    public $saveDistances = null;

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
            [['uid', 'name', 'rdpu', 'regionUID', 'pointUID', 'nearElevatorUID', 'mainCultureUID'], 'string', 'max' => 250],
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
    public function fields()
    {
        $fields = ['nearElevator', 'contacts', 'cultures', 'mainCulture', 'distances', 'region', 'point', 'processedSquare', 'mainContact',
            'sender' => function ($model) {
                foreach (static::distributionStatuses() as $item) {
                    if ($item['id'] == (int)$model->sender) {
                        return $item;
                    }
                }
                return [
                    'name' => 'Не известно',
                    'id' => 0
                ];
            }
        ];
        return array_merge(parent::fields(), $fields);
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return ['contacts', 'region', 'point', 'nearElevator',
            'cultures.culture', 'cultures.regionCulture', 'distances.point', 'mainCulture',
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
     * @param $contacts
     */
    public function setContacts($contacts)
    {
        $this->saveContacts = $contacts;
    }

    /**
     * @param $value
     */
    public function setCultures($value)
    {
        $this->saveCultures = $value;
    }

    /**
     * @param $value
     */
    public function setDistances($value)
    {
        $this->saveDistances = $value;
    }

    /**
     *
     */
    public function saveContacts()
    {
        if ($this->saveContacts === null)
            return;
        $transaction = Contacts::getDb()->beginTransaction();
        try {
            Contacts::deleteAll(['firmUID' => $this->uid]);
            if ($this->saveContacts && is_array($this->saveContacts)) {
                foreach ($this->saveContacts as $contactInfo) {
                    $object = new Contacts();
                    $object->attributes = $contactInfo;
                    $object->firmUID = $this->uid;
                    $object->save();
                    if ($object->hasErrors()) {
                        $this->addErrors(['contacts' => $object->getErrors()]);
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        } catch (\Throwable $throwable) {
            $transaction->rollBack();
            throw $throwable;
        }

    }

    /**
     * @param bool $delete
     */
    public function saveCultures($delete = true)
    {
        if ($this->saveCultures === null)
            return;
        if ($delete) {
            FirmCultures::deleteAll(['firmUID' => $this->uid]);
        }
        if ($this->saveCultures && is_array($this->saveCultures)) {
            foreach ($this->saveCultures as $info) {
                $object = new FirmCultures();
                $object->attributes = $info;
                $object->firmUID = $this->uid;
                $object->save();
                if ($object->hasErrors()) {
                    $this->addErrors(['сultures' => $object->getErrors()]);
                }
            }
        }
    }

    /**
     *
     */
    public function saveDistances()
    {

        if ($this->saveDistances === null)
            return;

        FirmsDistances::deleteAll(['firmUID' => $this->uid]);
        if ($this->saveDistances && is_array($this->saveDistances)) {
            foreach ($this->saveDistances as $item) {
                $instance = new FirmsDistances();
                $instance->attributes = $item;
                $instance->firmUID = $this->uid;
                $instance->save();
                if ($instance->hasErrors()) {
                    $this->addErrors(['distances' => $instance->getErrors()]);
                }
            }
        }
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
