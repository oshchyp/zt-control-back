<?php

namespace app\models;

use app\components\helper\Main;
use app\components\models\ModelAsResourceInterface;
use yii\web\Linkable;
use yii\web\Link;
use yii\helpers\Url;

/**
 * This is the model class for table "railwayTransit".
 *
 * @property int $id
 * @property string $uid
 * @property string $customerFirmUID
 * @property int $ownershipWagonID
 * @property int $wagonNumber
 * @property int $consignmentNumber
 * @property float $weight
 * @property float $loadingWeight
 * @property float $unloadingWeight
 * @property float $differentWeight
 * @property int $datePlane
 * @property string $destinationStationUID
 * @property string $departureStationUID
 * @property string $productUID
 * @property string $forwarderFirmUID
 * @property int $classID
 * @property string $class
 * @property int $dateArrival
 * @property float $price
 * @property float $tariff
 * @property float $additionalPrice       Дополнительные расходы
 * @property string $contractUID
 * @property int $statusID
 * @property string $addInfo
 * @property int $typeID
 * @property int $updated_at
 */
class RailwayTransit extends ActiveRecord implements Linkable, ModelAsResourceInterface
{

    public static $allInstances = null;

    const STATUS_ID_NEW = 1;

    const STATUS_ID_COMPLETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'railwayTransit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //   [['wagonNumber', 'consignmentNumber', 'contractUID'], 'required'],
            [['wagonNumber', 'consignmentNumber', 'datePlane', 'classID', 'dateArrival', 'statusID', 'ownershipWagonID', 'typeID'], 'integer'],
            [['weight', 'loadingWeight', 'unloadingWeight', 'price', 'additionalPrice', 'tariff'], 'number'],
            [['uid', 'customerFirmUID', 'destinationStationUID', 'departureStationUID', 'productUID', 'forwarderFirmUID', 'contractUID'], 'string', 'max' => 250],
            [['addInfo'], 'string'],
            // [['forwarderFirmName','customerFirmName','contractName'],'safe']
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
            'customerFirmUID' => 'Customer Firm Uid',
            'ownershipWagonID' => 'Ownership Wagon ID',
            'wagonNumber' => 'Wagon Number',
            'consignmentNumber' => 'Consignment Number',
            'weight' => 'Weight',
            'loadingWeight' => 'Loading Weight',
            'unloadingWeight' => 'Unloading Weight',
            'datePlane' => 'Date Plane',
            'destinationStationUID' => 'Destination Station Uid',
            'departureStationUID' => 'Departure Station Uid',
            'productUID' => 'Product Uid',
            'forwarderFirmUID' => 'Forwarder Firm Uid',
            'classID' => 'Class ID',
            'class' => 'Class',
            'dateArrival' => 'Date Arrival',
            'price' => 'Price',
            'tariff' => 'Tariff',
            'additionalPrice' => 'Additional Price',
            'contractUID' => 'Contract Uid',
            'statusID' => 'Status ID',
            'addInfo' => 'Add Info',
        ];
    }

    public function fields()
    {
        $fields = [
            'addInfo',
            'consignmentNumber',
            'loadingWeight',
            'price',
            'tariff',
            'uid',
            'unloadingWeight',
            'wagonNumber',
            'weight',
            'id',
            'contract',
            'product',
            'destinationStation',
            'departureStation',
            'customerFirm',
            'forwarderFirm',
            'ownershipWagon',
            'status',
            'differentWeight',
            'class',
            'classID',
            'type',
            'datePlane' => function (){
                 return $this->dateFormat('datePlane');
            },
            'dateArrival' => function(){
                return $this->dateFormat('dateArrival');
            }

        ];

        if (!$this->datePlane) {
            unset($fields['datePlane']);
        }

        if (!$this->dateArrival) {
            unset($fields['dateArrival']);
        }

        return $fields;
    }


    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (!$this->uid) {
            $this->uid = uniqid() . '_' . Main::generateUid();
        }

        DateSet::setTimestamp($this, ['dateArrival', 'datePlane'], $this->getAttributes());

        if (!static::findByIDAddField(static::statuses(), $this->statusID)) {
            $this->statusID = static::STATUS_ID_NEW;
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if ($this->consignmentNumber === null) {
            $this->consignmentNumber = 0;
        }
        $this->updated_at = time();
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function dateFormat($attr)
    {
         return DateSet::instance()->getDate($this->$attr);
    }

    public static function ownershipWagons()
    {
        return [
            [
                'id' => 1,
                'name' => 'ЦТЛ',
            ],
            [
                'id' => 2,
                'name' => 'СОБ',
            ],
        ];
    }

    public static function statuses()
    {
        return [
            [
                'id' => 1,
                'name' => 'Новый',
            ],
            [
                'id' => 2,
                'name' => 'Завершенный',
            ],
        ];
    }

    public static function classes()
    {
        return [
            [
                'id' => 1,
                'name' => '1кл',
            ],
            [
                'id' => 2,
                'name' => '2кл',
            ],
            [
                'id' => 3,
                'name' => '3кл',
            ],
            [
                'id' => 4,
                'name' => '4кл',
            ],
            [
                'id' => 5,
                'name' => '5кл',
            ],
            [
                'id' => 6,
                'name' => '6кл',
            ],
        ];
    }

    public static function types()
    {
        return [
            [
                'id' => 1,
                'name' => 'EXW',
            ],
            [
                'id' => 2,
                'name' => 'SPT',
            ],
        ];
    }

    public static function findByIDAddField($array, $id)
    {
        foreach ($array as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }

        return null;
    }

    public function getWagonName()
    {
        if ($this->wagonNumber) {
            return $this->wagonNumber;
        } elseif ($this->uid) {
            return $this->wagonNumber;
        } else {
            return 'No name wagon';
        }
    }

    public function getContract()
    {
        return $this->hasOne(Contracts::className(), ['uid' => 'contractUID']);
    }


    public static function relations()
    {
        return ['contract', 'product', 'destinationStation', 'departureStation', 'customerFirm', 'forwarderFirm'];
    }

    public static function extraDataToSave()
    {
        return [
            'contracts' => Contracts::find()->all(),
            'cultures' => Culture::find()->all(),
            'stations' => Stations::find()->asArray()->all(),
            'firmsRT' => RTFirms::find()->asArray()->all(),
            'ownershipWagons' => static::ownershipWagons(),
            'statuses' => static::statuses(),
            'statusIdNew' => static::STATUS_ID_NEW,
            'statusIdCompleted' => static::STATUS_ID_COMPLETED,
            'classes' => static::classes(),
            'types' => static::types()
        ];
    }

    /**
     * @param string $name
     * @param string $nameAttribute
     * @param \yii\db\ActiveRecord $className
     * @return \yii\db\ActiveRecord
     */
    public static function saveRecordByName($name = '', $className, $nameAttribute = 'name')
    {
        $model = new $className();
        $model->attributes = [$nameAttribute => $name];
        $model->setUid();
        if (!$model->save()) {
            $model->uid = null;
        }
        return $model;
    }

    public function getDifferentWeight()
    {
        if ($this->loadingWeight == null || $this->unloadingWeight == null)
            return null;
        $diff = $this->loadingWeight - $this->unloadingWeight;
        //  dump($diff,1);
        return round($diff, 2);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Culture::className(), ['uid' => 'productUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinationStation()
    {
        return $this->hasOne(Stations::className(), ['uid' => 'destinationStationUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartureStation()
    {
        return $this->hasOne(Stations::className(), ['uid' => 'departureStationUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerFirm()
    {
        return $this->hasOne(RTFirms::className(), ['uid' => 'customerFirmUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForwarderFirm()
    {
        return $this->hasOne(RTFirms::className(), ['uid' => 'forwarderFirmUID']);
    }

    public function getOwnershipWagon()
    {
        return static::findByIDAddField(static::ownershipWagons(), $this->ownershipWagonID);
    }

    public function getStatus()
    {
        return static::findByIDAddField(static::statuses(), $this->statusID);
    }

    public function getClass()
    {
        return static::findByIDAddField(static::classes(), $this->classID);
    }

    public function getType()
    {
        return static::findByIDAddField(static::types(), $this->typeID);
    }

    public static function findByUID($uid)
    {
        return RailwayTransit::find()->where(['uid' => $uid])->one();
    }

    /**
     * @param $id
     * @return RailwayTransit|array|null|\yii\db\ActiveRecord
     */
    public static function findByID($id){
        return RailwayTransit::find()->where(['id' => $id])->one();
    }

    /**
     * Returns a list of links.
     *
     * Each link is either a URI or a [[Link]] object. The return value of this method should
     * be an array whose keys are the relation names and values the corresponding links.
     *
     * If a relation name corresponds to multiple links, use an array to represent them.
     *
     * For example,
     *
     * ```php
     * [
     *     'self' => 'http://example.com/users/1',
     *     'friends' => [
     *         'http://example.com/users/2',
     *         'http://example.com/users/3',
     *     ],
     *     'manager' => $managerLink, // $managerLink is a Link object
     * ]
     * ```
     *
     * @return array the links
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['/'], true),
        ];
    }
}
