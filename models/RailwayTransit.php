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
 * @property int $wagonNumber
 * @property int $consignmentNumber
 * @property double $weightShipping
 * @property double $factShipment
 * @property double $weightLoading
 * @property int $datePlane
 * @property string $destinationStationUID
 * @property string $productUID
 * @property string $forwarderUID
 * @property int $classID
 * @property int $dateArrival
 * @property double $price
 * @property double $tariff
 * @property string $contractUID
 * @property int $statusID
 */
class RailwayTransit extends \yii\db\ActiveRecord
{
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
            [['wagonNumber', 'consignmentNumber','contractUID','uid'], 'required'],
            [['wagonNumber', 'consignmentNumber', 'datePlane', 'classID', 'dateArrival', 'statusID'], 'integer'],
            [['weightShipping', 'factShipment', 'weightLoading', 'price', 'tariff'], 'number'],
            [['uid', 'customerFirmUID', 'executorFirmUID', 'destinationStationUID', 'productUID', 'forwarderUID', 'contractUID'], 'string', 'max' => 250],
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
            'wagonNumber' => 'Wagon Number',
            'consignmentNumber' => 'Consignment Number',
            'weightShipping' => 'Weight Shipping',
            'factShipment' => 'Fact Shipment',
            'weightLoading' => 'Weight Loading',
            'datePlane' => 'Date Plane',
            'destinationStationUID' => 'Destination Station Uid',
            'productUID' => 'Product Uid',
            'forwarderUID' => 'Forwarder Uid',
            'classID' => 'Class ID',
            'dateArrival' => 'Date Arrival',
            'price' => 'Price',
            'tariff' => 'Tariff',
            'contractUID' => 'Contract Uid',
            'statusID' => 'Status ID',
        ];
    }

    public function fields(){
        $fields = parent::fields();
        $fields = array_merge($fields,['contract','product','destinationStation']);
        $fields['datePlane'] = function(){
            return date('d.m.Y',(int)$this->datePlane);
        };
        return $fields;
    }

    public static function relations(){
        return ['contract','product','destinationStation'];
    }


    public function beforeValidate()
    {
        if (!$this->uid){
            $this->uid = uniqid().'_'.Main::generateUid();
        }

        if ($this->datePlane && (int)preg_replace('/[^0-9]/', '', $this->datePlane) !== (int)$this->datePlane){
            $this->datePlane = (new \DateTime($this->datePlane)) -> getTimestamp();
        }

        if ($this->dateArrival && (int)preg_replace('/[^0-9]/', '', $this->dateArrival) !== (int)$this->dateArrival){
            $this->dateArrival = (new \DateTime($this->dateArrival)) -> getTimestamp();
        }

        return parent::beforeValidate();
    }


    public function getWagonName(){
        if ($this->wagonNumber)
            return $this->wagonNumber;
        else if ($this->uid){
            return $this->wagonNumber;
        } else {
            return 'No name wagon';
        }
    }

    public function getContract(){
        return $this->hasOne(Contracts::className(),['uid'=>'contractUID']);
    }

    public function getProduct(){
        return $this->hasOne(Culture::className(),['uid'=>'productUID']);
    }

    public function getDestinationStation(){
        return $this->hasOne(Stations::className(),['uid'=>'destinationStationUID']);
    }

    public static function findByUID($uid){
        return RailwayTransit::find()->where(['uid' => $uid])->one();
    }


}
