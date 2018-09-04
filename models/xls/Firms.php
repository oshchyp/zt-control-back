<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.09.2018
 * Time: 11:58
 */

namespace app\models\xls;


use app\models\ActiveRecord;
use app\models\Contacts;
use app\models\Culture;
use app\models\Elevators;
use app\models\FirmCultures;
use app\models\Points;
use app\models\Regions;
use yii\helpers\ArrayHelper;

class Firms extends ModelExcel implements ModelExcelInterface
{

    public $firmModel;

    public $firmCultures = null;

    public $regionInstance = null;

    public $pointInstances = [];

    public $nearElevatorInstance;

    public $distances = null;

    public function runObjectDataProcessing()
    {

       $this->firmModel = \app\models\Firms::getInstanceByRdpu($this->getLoadInfo('rdpu'));
       $this->firmModel->setEventsParsing();
       $this->firmModel->attributes = $this->getFirmModelAttributes();
       if($this->firmModel->save()){
          $this->saveFirmCultures();
          $this->saveContact();
          $this->saveDistances();
       }

     }

    public function getFirmModelAttributes(){
        return [
            'name' => $this->getLoadInfo('name'),
            'rdpu' => $this->getLoadInfo('rdpu'),
            'square' => $this->getLoadInfo('SAll'),
            'regionUID' => $this->getRegionInstance()->uid,
            'pointUID' => $this->getPointInstance()->uid,
            'nearElevatorUID' => $this->getNearElevatorInstance()->uid,
            'cultures' => $this->getFirmCultures(),
            'distances' => $this->getDistances(),
            'sender' => $this->getLoadInfo('sender') == 'рассылка' ? 1 : 0,
        ];
    }


    public function saveDistances(){
        $this->firmModel->saveDistances();
    }

    public function getDistances(){
        if ($this->distances === null){
            $this->distances=[];
            foreach ($this->distRules() as $rule){
                if ($this->getLoadInfo($rule[0])){
                    $this->distances[$rule[0]] = [
                        'pointUID' => $this->getPointInstanceByName($rule[1])->uid,
                        'distance' => $this->getLoadInfo($rule[0])
                    ];
                }
            }
        }
        return $this->distances;
    }




    public function saveFirmCultures(){
        FirmCultures::deleteAll(['firmUID'=>$this->firmModel->uid,'year'=>date('Y')]);
        $this->firmModel->saveCultures(false);
    }

    public function getFirmCultures(){
        if ($this->firmCultures === null){
            $this->firmCultures=[];
            foreach ($this->culturesRules() as $rule){
                $SAttr = $rule[0].'S';
                $WAttr = $rule[0].'W';
                if ($this->getLoadInfo($SAttr) || $this->getLoadInfo($WAttr)){
                    $this->firmCultures[$rule[0]] = [
                        'cultureUID' => Culture::getInstanceByNameOrSave($rule[1])->uid,
                        'square' => $this->getLoadInfo($SAttr),
                        'weight' => $this->getLoadInfo($WAttr),
                        'year' => date('Y')
                    ];
                }
            }
        }

        return $this->firmCultures;
    }



    public function saveContact(){
        $instance = $this->getContactInstance();
        $instance->setEventsParsing();
        $instance->attributes = $this->getContactAttributes();
        $instance->firmUID = $this->firmModel->uid;
        $instance->save();
    }

    public function getContactInstance(){
       $contacts = Contacts::getAllInstances();
      // dump($contacts,1);
       if ($contacts){
           foreach ($contacts as $item){
               if ($item->firmUID == $this->firmModel->uid) {
                   if ($item->phone == $this->getContactPhone() || $item->email == $this->getLoadInfo('contactEmail') || $item->name == $this->getLoadInfo('contactName')) {
                       return $item;
                   }
               }
           }
       }
       return new Contacts();
    }


    public function getContactAttributes(){
        return [
            'name' => $this->getLoadInfo('contactName'),
            'phone' => $this->getContactPhone(),
            'email' => str_replace(' ','',$this->getLoadInfo('contactEmail')),
        ];
    }

    public function getRegionInstance(){
        if ($this->regionInstance === null) {
            $this->regionInstance = Regions::getInstanceByAttrValue($this->getRegionOrPointName('region'), 'name');

            if ($this->regionInstance->getIsNewRecord()) {
                $this->regionInstance->setEventsParsing();
                $this->regionInstance->name = $this->getLoadInfo('region');
                $this->regionInstance->save();
            }
        }
        return $this->regionInstance;
    }

    public function getPointInstance(){
        return $this->getPointInstanceByName('point',$this->getRegionInstance()->uid);
    }

    public function getPointInstanceByName($key,$regionUID=null){
        if (!key_exists($key,$this->pointInstances)){
            $this->pointInstances[$key] = Points::getInstanceByAttrValue($this->getRegionOrPointName($key), 'name');
            if ($this->pointInstances[$key]->getIsNewRecord()) {
                $this->pointInstances[$key]->setEventsParsing();
                if ($regionUID) {
                    $this->pointInstances[$key]->regionUID = $regionUID;
                }
                $this->pointInstances[$key]->name = $this->getRegionOrPointName($key);
                $this->pointInstances[$key]->save();
            }
        }
        return $this->pointInstances[$key];
    }

    public function getNearElevatorInstance(){
        if ($this->nearElevatorInstance === null){
            $this->nearElevatorInstance = Elevators::getInstanceByAttrValue($this->getLoadInfo('nearElevator'),'name');
            if ($this->nearElevatorInstance->getIsNewRecord()){
                $this->nearElevatorInstance->setEventsParsing();
                $this->nearElevatorInstance->name = $this->getLoadInfo('nearElevator');
                $this->nearElevatorInstance->save();
            }
        }
        return $this->nearElevatorInstance;
    }



    public function getContactPhone(){
        if (iconv_strlen((string)$this->getLoadInfo('contactPhone')) == 9){
            return '+380'.$this->getLoadInfo('contactPhone');
        }
        return  $this->getLoadInfo('contactPhone');
    }

    public function getRegionOrPointName($key){
        $converter = [
            'Арцизский' => 'Арцизський'
        ];
        $name = $this->getLoadInfo($key);
        return isset($converter[$name]) ? $converter[$name] : $name;
    }

    public function culturesRules(){
        return [
            ['barley','ячмінь'],
            ['wheat','пшениця'],
            ['rape','рапс'],
            ['sunflower','соняшник'],
            ['corn','кукурудза'],
            ['bean','бобові (горох, боби)'],
            ['sorghum','сорго'],
            ['flax','льон'],
            ['nut','нут'],
            ['millet','просо'],
        ];
    }

    public function distRules(){
        return [
            ['ArcisDist','Арциз'],
            ['SarataDist','сарата'],
            ['BeresinoDist','Березине'],
            ['IzmailDist','Ізмаїл'],
            ['KulachevaDist','Кулевча'],
            ['OdessaDist','Одеса']
        ];
    }

    public static function excelRules(){
        return [
            'A'=>'sender',
            'B'=>'rdpu',
            'C'=>'name',
            'D'=>'SAll',
            'E'=>'SProcessed',
            'F'=>'contactName',
            'G'=>'contactPhone',
            'H'=>'contactEmail',
            'I'=>'region',
            'J'=>'nearElevator',
            'K'=>'point',
        //    'L'=>'',
            'M'=>'barleyS',
          //  'N'=>'',
            'O'=>'barleyW',
            'P'=>'wheatS',
           // 'Q'=>'',
            'R'=>'wheatW',
            'S'=>'rapeS',
            'T'=>'',
            'U'=>'rapeW',
            'V'=>'sunflowerS',
     //       'W'=>'',
            'X'=>'sunflowerW',
            'Y'=>'cornS',
      //      'Z'=>'',
            'AA'=>'cornW',
            'AB'=>'beanW',
            //'AC'=>'',
            'AD'=>'beanS',
            'AE'=>'sorghumS',
         //   'AF'=>'',
            'AG'=>'sorghumW',
            'AH'=>'flaxW',
           // 'AI'=>'',
            'AJ'=>'flaxS',
            'AK' => 'nutS',
         //   'AL' => '',
            'AM' => 'nutW',
            'AN' => 'milletS',
        //    'AO' => '',
            'AP' => 'milletW',
            'AQ' => 'ArcisDist',
            'AR' => 'SarataDist',
            'AS' => 'BeresinoDist',
            'AT' => 'IzmailDist',
            'AU' => 'KulachevaDist',
            'AV' => 'OdessaDist',

        ];
    }
}