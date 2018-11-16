<?php

namespace app\models;

use app\models\helper\Main;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "firms".
 *
 * @property int $id
 * @property string uid
 * @property string $name
 * @property string $director
 * @property string $rdpu
 * @property int $contactUID
 * @property string $contactPost
 * @property mixed culturesRelation
 * @property Contacts[] contactsRelation
 * @property mixed distancesRelation
 * @property mixed cultures
 * @property contacts
 */
class Firms extends ActiveRecord
{

    /**
     * @var null
     */
    public static $allInstances = null;

    /**
     * @var bool
     */
    public $addInstanceAfterSave = false;

    /**
     * @var null
     */
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
     * @var null
     */
    private $_processedSquare = null;
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
            [['uid'],'unique'],
            [['uid', 'name'], 'required'],
            [['square','processedSquare'], 'number'],
            [['uid', 'name', 'rdpu', 'regionUID', 'pointUID','nearElevatorUID','mainCultureUID'], 'string', 'max' => 250],
            ['sender', 'in', 'range' => ArrayHelper::getColumn(static::distributionStatuses(),'id')],
            [['contacts','cultures','distances'],'safe'],
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
    public static function viewFields(){
        return ['id','uid','name','rdpu','square','regionUID','pointUID','nearElevatorUID','nearElevator','contacts','cultures','mainCulture','distances','region', 'point','processedSquare','mainContact',
            'sender' => function ($model){
                 foreach (static::distributionStatuses() as $item){
                      if ($item['id'] == $model->sender){
                          return $item;
                      }
                 }
                 return [
                     'name' => 'Не известно',
                     'id' => 0
                 ];
            }
        ];
    }

    /**
     * @return array
     */
    public static function relations(){
      return ['contactsRelation', 'region', 'point','nearElevator.balanced.culture',
            'culturesRelation.culture','distancesRelation.point'
        ];
    }

    /**
     * @return array
     */
    public static function distributionStatuses(){
        return [
            [
                'id'=>1,
                'name' => 'Не участвует'
            ],
            [
                'id'=>2,
                'name' => 'Участвует'
            ],
            [
                'id'=>3,
                'name' => 'Добавить в рассылку'
            ]
        ];
    }

    /**
     * @param $contacts
     */
    public function setContacts($contacts){
           $this->saveContacts = $contacts;
    }

    /**
     * @param $value
     */
    public function setCultures($value){
         $this->saveCultures = $value;
    }

    /**
     * @param $value
     */
    public function setDistances($value){
        $this->saveDistances = $value;
    }

    /**
     *
     */
    public function saveContacts(){
        if ($this->saveContacts === null)
            return;
        Contacts::deleteAll(['firmUID'=>$this->uid]);
        if ($this->saveContacts && is_array($this->saveContacts)){
            foreach ($this->saveContacts as $contactInfo){
                $object = new Contacts();
                $object->attributes = $contactInfo;
                $object->firmUID = $this->uid;
                $object->save();
                if ($object->hasErrors()){
                    $this->addErrors(['contacts' => $object->getErrors()]);
                }
            }
        }

    }

    /**
     * @param bool $delete
     */
    public function saveCultures($delete=true){
        if ($this->saveCultures === null)
            return;
        if ($delete){
            FirmCultures::deleteAll(['firmUID' => $this->uid]);
        }
        if ($this->saveCultures && is_array($this->saveCultures)){
            foreach ($this->saveCultures as $info){
                $object = new FirmCultures();
                $object->attributes = $info;
                $object->firmUID = $this->uid;
                $object->save();
                if ($object->hasErrors()){
                    $this->addErrors(['сultures' => $object->getErrors()]);
                }
            }
        }
    }

    /**
     *
     */
    public function saveDistances(){

        if ($this->saveDistances === null)
            return;

        FirmDistances::deleteAll(['firmUID'=>$this->uid]);
        if ($this->saveDistances && is_array($this->saveDistances)){
            foreach ($this->saveDistances as $item){
                $instance = new FirmDistances();
                $instance->attributes = $item;
                $instance->firmUID = $this->uid;
                $instance->save();
                if ($instance->hasErrors()){
                    $this->addErrors(['distances' => $instance->getErrors()]);
                }
            }
        }
    }


    /**
     * @return float|int|null
     */
    public function getProcessedSquare(){

        if ($this->_processedSquare === null){
            $this->_processedSquare = 0;
            if ($this->cultures){
                foreach ($this->cultures as $item){
                    if ($item['year'] == date('Y')){
                        $this->_processedSquare += (float)$item['square'];
                    }
                }
            }
        }

        return $this->_processedSquare;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion(){
        return $this->hasOne(Regions::className(),['uid'=>'regionUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoint(){
        return $this->hasOne(Points::className(),['uid'=>'pointUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNearElevator(){
        return $this->hasOne(Elevators::className(),['uid'=>'nearElevatorUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistancesRelation(){
        return $this->hasMany(FirmDistances::className(),['firmUID'=>'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactsRelation(){
        return $this->hasMany(Contacts::className(),['firmUID'=>'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCulturesRelation(){
        return $this->hasMany(FirmCultures::className(),['firmUID'=>'uid'])->orderBy(['year' => SORT_DESC]);
    }

    /**
     * @return array
     */
    public function getDistances(){
        return ArrayHelper::toArray($this->distancesRelation,[FirmDistances::className()=>FirmDistances::viewFields()]);
    }

    /**
     * @return array
     */
    public function getContacts(){
        return ArrayHelper::toArray($this->contactsRelation,[Contacts::className()=>Contacts::viewFields()]);
    }

    /**
     * @return Contacts|null
     */
    public function getMainContact(){
        $contacts = $this->contactsRelation;
        if ($contacts){
            foreach ($contacts as $instance){
                if ($instance->main){
                    return $instance;
                }
            }
        }
        return null;
    }

    public function getMainCulture(){
        return $this->hasOne(Culture::className(),['uid'=>'mainCultureUID']);
    }

    /**
     * @return array
     */
    public function getCultures(){
        return ArrayHelper::toArray($this->culturesRelation,[FirmCultures::className()=>FirmCultures::viewFields()]);
    }

    /**
     * @param $rdpu
     * @return Firms
     */
    public static function getInstanceByRdpu($rdpu)
    {
        return $rdpu ? parent::getInstanceByAttrValue($rdpu, 'rdpu') : new static(); // TODO: Change the autogenerated stub
    }


}
