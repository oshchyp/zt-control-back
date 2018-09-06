<?php

namespace app\models;

use app\models\helper\Main;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "firms".
 *
 * @property int $id
 * @property string $name
 * @property string $director
 * @property string $rdpu
 * @property int $contactUID
 * @property string $contactPost
 */
class Firms extends ActiveRecord
{

    public static $allInstances = null;

    public $addInstanceAfterSave = false;

    public $saveContacts = null;

    public $saveCultures = null;

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
            [['uid'],'unique'],
            [['uid', 'name'], 'required'],
            [['square'], 'number'],
            [['uid', 'name', 'rdpu', 'regionUID', 'pointUID','nearElevatorUID'], 'string', 'max' => 250],
            ['sender', 'in', 'range' => [0, 1]],
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

    public static function viewFields(){
        return ['id','uid','name','director','rdpu','square','regionUID','pointUID','nearElevatorUID','nearElevator','contacts',
            'cultures','distances',
            'region', 'point'];
    }

    public static function viewRelations(){
        return ['contactsRelation', 'region', 'point','nearElevator',
            'culturesRelation.culture','distancesRelation.point'
        ];
    }


    public function setContacts($contacts){
           $this->saveContacts = $contacts;
    }

    public function setCultures($value){
         $this->saveCultures = $value;
    }

    public function setDistances($value){
        $this->saveDistances = $value;
    }

    public function saveContacts(){
        if ($this->saveContacts === null)
            return;
        Contacts::updateAll(['firmUID'=>NULL],['firmUID' => $this->uid]);
        if ($this->saveContacts && is_array($this->saveContacts)){
            foreach ($this->saveContacts as $contactInfo){
                $object = Contacts::getInstanceByUIDWithoutFirm(ArrayHelper::getValue($contactInfo,'uid'));
                $object->attributes = $contactInfo;
                $object->firmUID = $this->uid;
                $object->save();
                if ($object->hasErrors()){
                    $this->addErrors(['contacts' => $object->getErrors()]);
                }
            }
        }
    }

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

    public function saveDistances(){

        if ($this->saveDistances === null)
            return;

       // dump($this->saveDistances===null,1);
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


    public function getDirector(){
        if ($this->contacts){
            foreach ($this->contacts as $contactInfo){
                if (isset($contactInfo['post']['director']) && $contactInfo['post']['director']){
                    return $contactInfo;
                }
            }
        }
    }

    public function getRegion(){
        return $this->hasOne(Regions::className(),['uid'=>'regionUID']);
    }

    public function getPoint(){
        return $this->hasOne(Points::className(),['uid'=>'pointUID']);
    }

    public function getNearElevator(){
        return $this->hasOne(Elevators::className(),['uid'=>'nearElevatorUID']);
    }

    public function getDistancesRelation(){
        return $this->hasMany(FirmDistances::className(),['firmUID'=>'uid']);
    }

    public function getContactsRelation(){
        return $this->hasMany(Contacts::className(),['firmUID'=>'uid']);
    }

    public function getCulturesRelation(){
        return $this->hasMany(FirmCultures::className(),['firmUID'=>'uid'])->orderBy(['year' => SORT_DESC]);
    }

    public function getDistances(){
        return ArrayHelper::toArray($this->distancesRelation,[FirmDistances::className()=>FirmDistances::viewFields()]);
    }

    public function getContacts(){
        return ArrayHelper::toArray($this->contactsRelation,[Contacts::className()=>Contacts::viewFields()]);
    }

    public function getCultures(){
        return ArrayHelper::toArray($this->culturesRelation,[FirmCultures::className()=>FirmCultures::viewFields()]);
    }

    public static function getInstanceByRdpu($rdpu)
    {
        return $rdpu ? parent::getInstanceByAttrValue($rdpu, 'rdpu') : new static(); // TODO: Change the autogenerated stub
    }


}
