<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 31.08.2018
 * Time: 10:44
 */

namespace app\models;



use yii\base\Model;
use yii\helpers\ArrayHelper;

class RailwayTransitMultiSave extends Model
{

    public $wagons;

    public $generalInfo;

    private $_savedData = [];


    /**
     * @return array
     */
    public function rules(){
        return [
            [['wagons','generalInfo'], 'safe']
        ];
    }

    public function save($newRecord=false){
        $this->_savedData = [];
        if (!$this->wagons || !is_array($this->wagons)){
            $this->wagons[] = [];
        }
        $this->saveRecordsByName();
        $this->saveContractDate();
        foreach ($this->wagons as $wagonInfo){
            $instance = RailwayTransitWagons::getInstanceRTbyData($wagonInfo,$newRecord);
            $instance->attributes = $this->generalInfo;
            $instance->save();
            if ($instance->hasErrors()){
                $this->addErrors(['wagon '.$instance->getWagonName() => $instance->getErrors()]);
            }
            $this->_savedData[] = $instance;
         }
    }

    public function saveRecordsByName(){
        $rules = [
            ['contract',Contracts::className()],
            ['customerFirm',RTFirms::className()],
            ['forwarderFirm',RTFirms::className()],
        ];

        foreach ($rules as $item){
            $attributeName = $item[0].'Name';
            $attributeUID = $item[0].'UID';
            if ($name = ArrayHelper::getValue($this->generalInfo,$attributeName)){
                $this->generalInfo[$attributeUID] = RailwayTransit::saveRecordByName($name,$item[1])->uid;
            }
        }
    }

    public function saveContractDate(){

        if (isset($this->generalInfo['contractUID']) && isset($this->generalInfo['contractDate'])){
            $contractModel = Contracts::find()->where(['uid'=>$this->generalInfo['contractUID']])->one();
            $contractDate = $this->generalInfo['contractDate'];
            if ($contractModel && $contractModel->getAllowEditDate() && $contractDate && is_string($contractDate)){
                $contractModel->contractDate = DateSet::instance()->getTimestamp($contractDate);
                $contractModel->save();
            }
        }

        if (
            key_exists('contractUID',$this->generalInfo) &&
            is_string($this->generalInfo['contractUID']) &&
            $contractModel = Contracts::find()->where(['uid'=>$this->generalInfo['contractUID']])->one()
        ) {
            if ($contractModel->getAllowEditDate()){

            }
        }
    }

    public function create(){
        $this->save(true);
    }

    public function update(){
        $this->save(false);
    }

    public static function getInstance($data=[]){
        $instance = new static();
        $instance->attributes = $data;
        return $instance;
    }

}