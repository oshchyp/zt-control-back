<?php

use yii\db\Migration;

/**
 * Class m190115_143513_save_to_db_hmelnik_firms
 */
class m190115_143513_save_to_db_hmelnik_firms extends Migration
{


    private $items;

    private $itemsBySomething = [];

    private $itemsByTable = [];

    private $uidArrays = [];

    /**
     * @return array
     */
    private function fields (){
        return [
            'A' => '',
            'B' => 'rdpu',
            'C' => 'name',
            'D' => 'rill',
            'E' => 'square',
            'G' => 'contact_name',
            'H' => 'contact_phone',
            'I' => 'contact_email',
            'J' => 'region',
            'K' => 'state',
            'L' => 'point',


            'N' => 'yachmin_s',
            'Q' => 'yachmin_yield',
            'R' => 'yachmin_weight',

            'U' => 'pshenica_s',
            'X' => 'pshenica_yield',
            'Y' => 'pshenica_weight',

            'AB' => 'raps_s',
            'AE' => 'raps_yield',
            'AF' => 'raps_weight',

            'AI' => 'podsolnuh_s',
            'AL' => 'podsolnuh_yield',
            'AM' => 'podsolnuh_weight',

            'AP' => 'kukuruza_s',
            'AS' => 'kukuruza_yield',
            'AT' => 'kukuruza_weight',

            'AW' => 'soya_s',
            'BO' => 'soya_yield',
            'BP' => 'soya_weight'
        ];
    }

    public function cultures(){
        return [
            [
                'name' => 'Ячмень',
                'alias' => 'yachmin',
            ],
            [
                'name' => 'Пшеница',
                'alias' => 'pshenica',
            ],
            [
                'name' => 'Рапс',
                'alias' => 'raps',
            ],
            [
                'name' => 'Подсолнечник',
                'alias' => 'podsolnuh',
            ],
            [
                'name' => 'Кукуруза',
                'alias' => 'kukuruza',
            ],
            [
                'name' => 'Соя',
                'alias' => 'soya',
            ]
        ];
    }

    public function farmCulturesByItem($item){

        $data = [];
        foreach ($this->cultures() as $v){
          //  dump($v['name']); die();
            if ($culture = $this->findItemBySomething('culture','name',$v['name'])) {
               // die('erferf');
                $info = [
                    'name' => $v['name'],
                    'cultureUID' => $culture['uid'],
                    'square' => (float)$item[$v['alias'].'_s'],
                    'yield' => (float)$item[$v['alias'].'_yield'],
                    'weight' => (float)$item[$v['alias'].'_weight'],
                    'year' => date('Y')
                ];

                if ($info['square'] || $info['yield'] || $info['weight']){
                    $data[] = $info;
                }
            }
        }
        return $data;
    }

    /**
     * @return mixed
     */
    private function getItems(){
        if ($this->items === null){
            $this->items = json_decode(file_get_contents(Yii::getAlias('@app/files/json/hmelnik_firms.json')),true);
            foreach ($this->items as $k=>$v){
                $this->items[$k] = $this->convertItem($v);
            }
        }
        return $this->items;
    }

    private function getItemsByTable($table){
        if (!isset($this->itemsByTable[$table])){
            $this->itemsByTable[$table] = (new \yii\db\Query())->from($table)->all();
        }
        return $this->itemsByTable[$table];
    }

    private function getItemsBySomething($table,$index){
        if (!isset($this->itemsBySomething[$table][$index])){
            $items = $this->getItemsByTable($table);
            $this->itemsBySomething[$table][$index] = \yii\helpers\ArrayHelper::index($items,$index);
        }
        return $this->itemsBySomething[$table][$index];
    }

    private function getItemsBySomethingArray($table,$index){
        if (!isset($this->itemsBySomething[$table][$index.'_array'])){
            $items = $this->getItemsByTable($table);
            if ($items){
                foreach ($items as $item){
                    $this->itemsBySomething[$table][$index.'_array'][$item[$index]][] = $item;
                }
            } else {
                $this->itemsBySomething[$table][$index.'_array'] = [];
            }
        }
        return $this->itemsBySomething[$table][$index.'_array'];
    }

    private function findItemBySomething($table,$index,$value){
         return \yii\helpers\ArrayHelper::getValue($this->getItemsBySomething($table,$index),$value);
    }

    private function findItemsBySomethingArray($table,$index,$value){
        return \yii\helpers\ArrayHelper::getValue($this->getItemsBySomethingArray($table,$index),$value);
    }

    /**
     * @param $item
     * @param $fields
     * @return array
     */
    public function convertDataToSave($item,$fields){
        $data = [];
        foreach ($fields as $k=>$v){
          //  dump($v); die();
            if (!is_numeric($k)){

                $data[$k] = $v;
            } else {
                $data[$v] = isset($item[$v]) ? $item[$v] : null;
            }
        }
      //  dump($data); die();
        return $data;
    }


    private function setValues($item){
        $rules = [
            'elevatorBit' => 4
        ];

        foreach ($rules as $k=>$v){
            $item[$k] = $v;
        }
        return $item;
    }

    /**
     * @param $item
     * @return array
     */
    private function  convertItem($item){
        $data = [];
        foreach ($this->fields() as $column=>$key){
            $data[$key] = isset($item[$column]) ? $item[$column] : null;
            $method = 'convertValue'.$key;
            if (method_exists($this,$method)){
                $data[$key] = $this->$method($data[$key]);
            }
        }
        return $this->setValues($data);
    }


    /**
     * @param $value
     * @return mixed|null|string|string[]
     */
    private function convertStringName($value){
        $value = mb_strtolower($value);
        $fc = mb_strtoupper(mb_substr($value, 0, 1));
        $value = $fc.mb_substr($value, 1);
        return trim($value);
    }

    private function generateUid($key){
        if (!isset($this->uidArrays[$key])){
            $this->uidArrays[$key] = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        }
        return $this->uidArrays[$key];
    }

    /**
     * @param $value
     * @return mixed|null|string|string[]
     */
    private function convertValueRegion($value){
       return $this->convertStringName($value);
    }

    /**
     * @param $value
     * @return mixed|null|string|string[]
     */
    private function convertValueState($value){
        return $this->convertStringName($value);
    }

    /**
     * @param $value
     * @return mixed|null|string|string[]
     */
    private function convertValuePoint($value){
        return $this->convertStringName($value);
    }


    private function findOrSaveRegion($name,$itemK=0){

        if (!$name){
            return [
                'uid' => null,
                'name' => '',
            ];
        }

        if(!$region = $this->findItemBySomething('regions','name',$name)){
            $region = [
                'uid' => $this->generateUid('regions_'.$itemK),
                'name' => $name
            ];
            $this->insert('regions',$region);
            $this->itemsBySomething['regions']['name'][$name] = $region;
            $this->itemsByTable['regions'] = $region;
        }

        return $region;
    }

    private function findOrSavePoint($name,$regionUID){
        if (!$name || !$regionUID){
            return [
                'uid' => null,
                'name' => '',
                'regionUID' => null
            ];
        }
        if(!$point = $this->findItemBySomething('points','name',$name)){
            $point = [
                'uid' => $this->generateUid('points_'.$regionUID),
                'name' => $name,
                'regionUID' => $regionUID
            ];
            $this->insert('points',$point);
            $this->itemsBySomething['points']['name'][$name] = $point;
            $this->itemsByTable['points'] = $point;
        }
        return $point;
    }

    /**
     * @param $itemK
     * @return null
     */
    private function saveFirm($itemK){
        $firmUID = $this->generateUid('firms_'.$itemK);
        $item =  $this->getItems()[$itemK];
        $this->items[$itemK]['uid']  = $firmUID;
        $item['uid'] = $firmUID;

        if (!$this->findItemBySomething('firms','rdpu',$item['rdpu'])) {
            $this->insert('firms', $this->convertDataToSave($item,['name','rdpu','uid','elevatorBit','square','regionUID','pointUID']));
            return $item;
        }
        return null;
    }

    /**
     * @param $item
     * @return bool
     */
    private function saveContacts ($item){
        $this->delete('contacts','firmUID="'.$item['uid'].'"');

        if ($item['contact_name'] || $item['contact_phone'] || $item['contact_email']) {
            $this->insert('contacts', [
                'name' => $item['contact_name'],
                'phone' => $item['contact_phone'],
                'email' => $item['contact_email'],
                'firmUID' => $item['uid'],
                'main' => 1
            ]);
            return true;
        }
        return false;
    }

    /**
     * @param $item
     * @param $k
     * @return array|null
     */
    public function saveFarm($item,$k){
        if ($item['regionUID'] || $item['pointUID']) {
            $this->delete('farms', 'firmUID = "' . $item['uid'] . '"');
            $farm = $this->convertDataToSave($item,[
                'uid'=>$this->generateUid('farms_'.$k),
                'firmUID'=>$item['uid'],
                'square' => (float)$item['square'],
                'regionUID','pointUID',
            ]);
            $this->insert('farms',$farm);
            return $farm;
        }
        return null;
    }

    /**
     * @param $item
     * @param $farm
     */
    public function saveFarmCultures($item,$farm){
        if ($farm && $cultures = $this->farmCulturesByItem($item)){
            $this->delete('farmCultures',['farmUID'=>$farm['uid']]);
            foreach ($cultures as $culture){
                $culture['farmUID'] = $farm['uid'];
                if (!$culture['weight']){
                    $culture['weight'] = $culture['yield'] * $culture['square'];
                }
                $this->insert('farmCultures',$this->convertDataToSave($culture,['cultureUID','farmUID','weight','square','year']));
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        foreach ($this->getItems() as $k=>$item) {
             $this -> items[$k]['regionUID'] = $this->findOrSaveRegion($item['region'],$k)['uid'];
             $this -> items[$k]['pointUID'] = $this->findOrSavePoint($item['point'],$this -> items[$k]['regionUID'])['uid'];
             if ($item=$this->saveFirm($k)){
                 $this->saveContacts($item);
                 $farm = $this->saveFarm($item,$k);
                 $this->saveFarmCultures($item,$farm);
             }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       foreach ($this->getItems() as $item){
           if ($firmModel = $this->findItemBySomething('firms','rdpu',$item['rdpu'])){
               $this->delete('firms',['uid'=>$firmModel['uid']]);
               $this->delete('contacts',['firmUID'=>$firmModel['uid']]);

               if ($farms = $this->findItemsBySomethingArray('farms','firmUID',$firmModel['uid'])){
                   $this->delete('farms',['firmUID'=>$firmModel['uid']]);
                   foreach ($farms as $farItem){
                       $this->delete('farmCultures',['farmUID'=>$farItem['uid']]);
                   }
               }

               $this->delete('points','id > 786');
               $this->delete('regions','id > 55');
           }
       }
    }


}

////points 786
/// regions 55
///
