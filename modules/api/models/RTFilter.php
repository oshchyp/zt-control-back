<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 23.10.2018
 * Time: 11:13
 */

namespace app\modules\api\models;


use app\components\filter\FilterDataInterface;
use app\components\filter\FilterDataTrait;
use app\models\RailwayTransit;

class RTFilter extends RailwayTransit implements FilterDataInterface
{

    use FilterDataTrait;

    public $sortFieldFlag = '|sort';

    public function rules()
    {
        return [
            [[
                'customerFirm.name','destinationStation.name','departureStation.name','product.name','forwarderFirm.name','class.name',
                'contract.name','ownershipWagon.name','addInfo','stringForSearchAll','datePlane', 'dateArrival','type.name','consignmentNumber',
                'wagonNumber','contract.contractDate'],'string'],
            [[
                 'weight', 'unloadingWeight','loadingWeight', 'price', 'additionalPrice',
                'tariff',  'differentWeight'], 'number'],
            [
                $this->sortFields(), 'sortValidate'
            ]
        ];
    }

    public function beforeValidate()
    {

        return true;
    }



    public function attributesAdd()
    {
        $array = ['contract.contractDate','type.name','class.name', 'ownershipWagon.name', 'differentWeight', 'customerFirm.name', 'destinationStation.name', 'departureStation.name', 'product.name', 'forwarderFirm.name', 'contract.name', 'stringForSearchAll'];
        $attributes = array_merge($array, $this->sortFields());
        return $attributes;
    }


    public function rulesFilter()
    {

        return [
            [['stringForSearchAll'], 'search',  [array_merge(['ownershipWagon.name', 'class.name','type.name',], $this->searchFields())]],
            [$this->rangeFields(), 'range', ['>', '=']],
            [$this->searchFields(), 'andWhere', ['like']],
            [['class.name'], 'searchInArray', [RailwayTransit::classes()]],
            [['ownershipWagon.name'], 'searchInArray', [RailwayTransit::ownershipWagons()]],
            [['type.name'], 'searchInArray', [RailwayTransit::types()]],
            [['datePlane', 'dateArrival','contract.contractDate'], 'date'],
            [['differentWeight'],'differentWeight'],
            [$this->sortFields(), 'sort'],
        ];
    }

    public function getDifferentWeight()
    {
        return $this->differentWeight;
    }

    public function attributesNameInQuery($attribute)
    {
        $arr = [
            'weight' => 'cast(`weight` as decimal(10,2))',
            'unloadingWeight' => 'cast(`unloadingWeight` as decimal(10,2))',
            'loadingWeight' => 'cast(`loadingWeight` as decimal(10,2))',
            'differentWeight' => '(`loadingWeight` - `unloadingWeight`)',
            'price' => 'cast(`price` as decimal(10,2))',
            'tariff' => 'cast(`tariff` as decimal(10,2))'
        ];
        foreach ($this->sortFields() as $attr) {
            $field = str_replace($this->sortFieldFlag, '', $attr);
            $arr[$attr] = isset($arr[$field]) ? $arr[$field] : $field;
        }
        return $arr;
    }

    public function searchFields()
    {
        return [
            'customerFirm.name',
            'wagonNumber',
            'consignmentNumber',
            'destinationStation.name',
            'departureStation.name',
            'product.name',
            'forwarderFirm.name',
            'addInfo',

           'contract.name'
        ];
    }

    public function rangeFields()
    {
        return ['weight', 'unloadingWeight', 'loadingWeight', 'price', 'tariff'];
    }

    /**
     * @return array
     */
    public function sortFields()
    {
        $sortFields = [
            'id', 'weight', 'unloadingWeight', 'loadingWeight', 'price', 'tariff', 'differentWeight',
            'datePlane', 'dateArrival', 'addInfo', 'contract.contractDate'
        ];
        $result = [];
        foreach ($sortFields as $attribute) {
            $result[] = $attribute . $this->sortFieldFlag;
        }
        return $result;
    }

    /**
     * @param $items
     * @param $string
     * @param string $nameAttr
     * @param string $idAttr
     * @return array
     */
    private static function _findIdsByNameFromItems($items, $string, $nameAttr = 'name', $idAttr = 'id')
    {
        $IDs = [];

        foreach ($items as $item) {
            if (strstr(mb_strtolower($item[$nameAttr]), mb_strtolower($string))) {
                $IDs[] = $item[$idAttr];
            }
        }
        if (!$IDs) {
            $IDs[] = -1;
        }
        return $IDs;
    }

    public static function getDataArray($string)
    {
        $string = str_replace(['_'],'',$string);
        $format = ['d', 'm', 'Y'];
        $explode = explode('.', $string);
        $result = [
            'Y' => null,
            'm' => null,
            'd' => null
        ];
        foreach ($format as $k => $v) {
            if (array_key_exists($k, $explode)) {
                $result[$v] = $explode[$k];
            }
        }
        return $result;
    }

    public function filterQueryDate($attribute, $value)
    {
        $dataArr = $this->getDataArray($value);
        foreach ($dataArr as $k => $v) {
            if ($v) {
                $this->getQuery()->andWhere(['FROM_UNIXTIME (' . $attribute . ',"%' . $k . '")' => $v]);
            }
        }
    }

    public function classNameFilterQuerySearch($attribute, $value)
    {
        return ['in', 'classID', static::_findIdsByNameFromItems(static::classes(), $value)];
    }

    public function ownershipWagonNameFilterQuerySearch($attribute, $value)
    {
        return ['in', 'ownershipWagonID', static::_findIdsByNameFromItems(static::ownershipWagons(), $value)];
    }

    public function typeNameFilterQuerySearch($attribute, $value)
    {
        return ['in', 'typeID', static::_findIdsByNameFromItems(static::types(), $value)];
    }

    public function filterQuerySearchInArray($attribute, $value, $items)
    {
        $this->getQuery()->andFilterWhere(['in', str_replace('.name', 'ID', $attribute), static::_findIdsByNameFromItems($items, $value)]);
    }

    public function filterQueryDifferentWeight($attribute, $value){
        $this->getQuery()
            ->andFilterWhere(['or',['>','(`loadingWeight` - `unloadingWeight`)',$value],['=','(`loadingWeight` - `unloadingWeight`)',$value]])
            ->andWhere(['not',['loadingWeight'=>null]])
            ->andWhere(['not',['unloadingWeight'=>null]])
            ->andWhere(['not',['loadingWeight'=>0]])
            ->andWhere(['not',['unloadingWeight'=>0]])
        ;
    }


}