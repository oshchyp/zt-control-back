<?php

namespace app\models;

use app\models\helper\Main;
use Yii;

/**
 * This is the model class for table "railwayTransit".
 *
 * @property int $id
 * @property string $uid
 * @property string $customerFirmUID
 * @property string $executorFirmUID
 * @property int $ownershipWagonID
 * @property int $wagonNumber
 * @property int $consignmentNumber
 * @property double $weight
 * @property double $loadingWeight
 * @property double $unloadingWeight
 * @property int $datePlane
 * @property string $destinationStationUID
 * @property string $departureStationUID
 * @property string $productUID
 * @property string $forwarderFirmUID
 * @property int $classID
 * @property string $class
 * @property int $dateArrival
 * @property double $price
 * @property double $tariff
 * @property double $additionalPrice Дополнительные расходы
 * @property string $contractUID
 * @property int $statusID
 * @property string $addInfo
 */

class RailwayTransit extends ActiveRecord
{


    public $dateFormat = 'Y.m.d';

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
            [['wagonNumber', 'consignmentNumber', 'contractUID'], 'required'],
            [['wagonNumber', 'consignmentNumber', 'datePlane', 'classID', 'dateArrival', 'statusID', 'ownershipWagonID'], 'integer'],
            [['weight', 'loadingWeight', 'unloadingWeight', 'price','additionalPrice', 'tariff'], 'number'],
            [['uid', 'customerFirmUID', 'executorFirmUID', 'destinationStationUID', 'departureStationUID', 'productUID', 'forwarderFirmUID', 'contractUID','class'], 'string', 'max' => 250],
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
            'executorFirmUID' => 'Executor Firm Uid',
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


    public static function ownershipWagons()
    {
        return [
            [
                'id' => 1,
                'name' => 'ЦТЛ'
            ],
            [
                'id' => 2,
                'name' => 'СОБ'
            ]

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
            ]
        ];
    }

    public static function findStatusByID($id){
        foreach (static::statuses() as $item){
            if ($item['id'] == $id)
                return $item;
        }
        return null;
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (!$this->uid) {
            $this->uid = uniqid() . '_' . Main::generateUid();
        }

        if ($this->datePlane && !is_numeric($this->datePlane)) {
            $this->datePlane = \DateTime::createFromFormat($this->dateFormat,$this->datePlane)->getTimestamp();
        }

        if ($this->dateArrival && !is_numeric($this->dateArrival)) {
            $this->dateArrival = \DateTime::createFromFormat($this->dateFormat,$this->dateArrival)->getTimestamp();
        }

        if (!static::findStatusByID($this->statusID)){
            $this->statusID = static::STATUS_ID_NEW;
        }

        return parent::beforeValidate();
    }


    public function getWagonName()
    {
        if ($this->wagonNumber)
            return $this->wagonNumber;
        else if ($this->uid) {
            return $this->wagonNumber;
        } else {
            return 'No name wagon';
        }
    }

    public function getContract()
    {
        return $this->hasOne(Contracts::className(), ['uid' => 'contractUID']);
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields = array_merge($fields, ['contract', 'product', 'destinationStation','departureStation', 'executorFirm', 'customerFirm', 'forwarderFirm', 'ownershipWagon','status','differentWeight']);
        $fields = [
                'datePlane' => function () {
                    return date($this->dateFormat, (int)$this->datePlane);
                },
                'dateArrival' => function () {
                    return date($this->dateFormat, (int)$this->dateArrival);
                },
//                'weight' => function (){
//                    return round($this->weight,2);
//                }
            ] + $fields;
        return $fields;
    }

    public static function relations()
    {
        return ['contract', 'product', 'destinationStation','departureStation', 'executorFirm', 'customerFirm', 'forwarderFirm'];
    }

    public static function extraDataToSave()
    {
        return [
            'contracts' => Contracts::find()->asArray()->all(),
            'cultures' => Culture::find()->asArray()->all(),
            'stations' => Stations::find()->asArray()->all(),
            'executorFirm' => ExecutorFirms::find()->asArray()->all(),
            'customerFirm' => CustomerFirms::find()->asArray()->all(),
            'forwarderFirm' => ForwarderFirms::find()->asArray()->all(),
            'ownershipWagons' => static::ownershipWagons(),
            'statuses' => static::statuses(),
            'statusIdNew' => static::STATUS_ID_NEW,
            'statusIdCompleted' => static::STATUS_ID_COMPLETED
        ];
    }

    public function getDifferentWeight(){
        return $this->loadingWeight - $this->unloadingWeight;
    }

    public function getProduct()
    {
        return $this->hasOne(Culture::className(), ['uid' => 'productUID']);
    }

    public function getDestinationStation()
    {
        return $this->hasOne(Stations::className(), ['uid' => 'destinationStationUID']);
    }

    public function getDepartureStation()
    {
        return $this->hasOne(Stations::className(), ['uid' => 'departureStationUID']);
    }

    public function getExecutorFirm()
    {
        return $this->hasOne(ExecutorFirms::className(), ['uid' => 'executorFirmUID']);
    }

    public function getCustomerFirm()
    {
        return $this->hasOne(CustomerFirms::className(), ['uid' => 'customerFirmUID']);
    }

    public function getForwarderFirm()
    {
        return $this->hasOne(ForwarderFirms::className(), ['uid' => 'forwarderFirmUID']);
    }

    public function getOwnershipWagon()
    {
        foreach (static::ownershipWagons() as $info) {
            if ($info['id'] == $this->ownershipWagonID)
                return $info;
        }
        return null;
    }

    public function getStatus(){
        return static::findStatusByID($this->statusID);
    }

    public static function findByUID($uid)
    {
        return RailwayTransit::find()->where(['uid' => $uid])->one();
    }


}
