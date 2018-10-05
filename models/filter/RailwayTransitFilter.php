<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 19.09.2018
 * Time: 12:04
 */

namespace app\models\filter;


use app\models\RailwayTransit;

class RailwayTransitFilter extends Filter implements ModelFilterInterface
{

    public $customerFirm;

    public $wagonNumber;

    public $consignmentNumber;

    public $weight;

    public $unloadingWeight;

    public $destinationStation;

    public $departureStation;

    public $product;

    public $forwarderFirm;

    public $class;

    public $loadingWeight;

    public $price;

    public $additionalPrice;

    public $tariff;

    public $contract;

    public $differentWeight;

    public $datePlane;

    public $dateArrival;

    public $status;

    public $ownershipWagon;

    public $addInfo;

    public $sortField = 'id';

    public $sortValue = 'DESC';

    public $stringForSearchAll;

    public function init()
    {
        if (!$this->getQuery()) {
            $query = RailwayTransit::find()->with(RailwayTransit::relations());
            $this->setQuery($query);
        }
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['stringForSearchAll', 'sortField', 'sortValue', 'datePlane','dateArrival'], 'string'];
        // $rules[] = [['stringForSearchAll','sortField','sortValue'],'string'];
        return $rules;
    }

    /**
     * @return mixed
     */
    public function getSortField()
    {
        $array = [
            'id' => 'railwayTransit.id',
            'weight' => 'weight',
            'unloadingWeight' => 'unloadingWeight',
            'loadingWeight' => 'loadingWeight',
            'price' => 'price',
            'tariff' => 'tariff',
            'differentWeight' => '`loadingWeight` - `unloadingWeight`',
            'datePlane' => 'datePlane',
            'dateArrival' => 'dateArrival',
            'addInfo' => 'addInfo'
        ];
        if ($this->sortField && key_exists($this->sortField, $array)) {
            return $array[$this->sortField];
        }
        return null;
    }


    /**
     * @return mixed
     */
    public function getSortValue()
    {
        if (mb_strtolower($this->sortValue) == 'asc') {
            return SORT_ASC;
        } else if (mb_strtolower($this->sortValue) == 'desc')
            return SORT_DESC;

        return null;
    }


    /**
     * @return mixed
     */
    public function stringForSearchAll()
    {
        return $this->stringForSearchAll;
    }

    /**
     * @return array
     */
    public function fieldsForSearch()
    {
        return [
            'customerFirm' => 'customerFirm.name',
            'wagonNumber',
            'consignmentNumber',
            'destinationStation' => 'destinationStation.name',
            'departureStation' => 'departureStation.name',
            'product' => 'product.name',
            'forwarderFirm' => 'forwarderFirm.name',
            'class',
            'addInfo',
            'additionalPrice',

            'contract' => 'contract.name',
            'ownershipWagon'
        ];
    }

    /**
     * @return mixed
     */
    public function fieldsComparisonMore()
    {
        return [
              'weight'=>'cast(`weight` as decimal(10,2))',
              'unloadingWeight'=>'cast(`unloadingWeight` as decimal(10,2))',
              'loadingWeight'=>'cast(`loadingWeight` as decimal(10,2))',
            ///  'differentWeight' => '(cast(`loadingWeight` as decimal(5,2)) - cast(`unloadingWeight` as decimal(5,2)))',
               'differentWeight' => '(`loadingWeight` - `unloadingWeight`)',
              'price'=>'cast(`price` as decimal(10,2))',
              'tariff'=>'cast(`tariff` as decimal(10,2))',
        ];
    }

    /**
     * @return array|mixed
     */
    public function fieldsForSearchAll()
    {
        return $this->fieldsForSearch();
    }

    /**
     * @return array|mixed
     */
    public function fieldsForSearchIndividual()
    {
        return $this->fieldsForSearch();
    }


    /**
     * @return mixed|void
     */
    public function fieldsForEquating()
    {
        // TODO: Implement fieldsForEquating() method.
    }

    /**
     * @return array|mixed
     */
    public function joinRules()
    {
        return [
            'customerFirm', 'destinationStation', 'departureStation', 'product', 'forwarderFirm', 'contract',
        ];
    }

    public static function getDataArray($string)
    {
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


    public function setDataWhere($attr){
        $dataArr = $this->getDataArray($this->$attr);
        foreach ($dataArr as $k => $v) {
            if ($v) {
                $this->getQuery()->andWhere(['FROM_UNIXTIME (`'.$attr.'`,"%' . $k . '")' => $v]);
            }
        }
       // dump($this->$differentWeight,1);
    }

    public function filterDate()
    {
        $this->setDataWhere('datePlane');
        $this->setDataWhere('dateArrival');
    }

    /**
     *
     */
    public function classSearchAll()
    {
        $this->searchAttrByItems(RailwayTransit::classes(), $this->stringForSearchAll(), 'classID');
    }

    /**
     *
     */
    public function classSearchIndividual()
    {
        $this->searchAttrByItems(RailwayTransit::classes(), $this->class, 'classID');
    }

    /**
     *
     */
    public function ownershipWagonSearchAll()
    {
        $this->searchAttrByItems(RailwayTransit::ownershipWagons(), $this->stringForSearchAll(), 'ownershipWagonID');
    }

    /**
     *
     */
    public function ownershipWagonSearchIndividual()
    {
        $this->searchAttrByItems(RailwayTransit::ownershipWagons(), $this->ownershipWagon, 'ownershipWagonID','andWhere');
    }

    /**
     * @param $items
     * @param $string
     * @param $fieldName
     * @param string $methodWhere
     * @param string $nameAttr
     * @param string $idAttr
     */
    private function searchAttrByItems($items, $string, $fieldName, $methodWhere = 'orWhere', $nameAttr = 'name', $idAttr = 'id')
    {
        if ($string) {
            $statusIDs = static::_findIdsByNameFromItems($items, $string, $nameAttr, $idAttr);
            $this->getQuery()->$methodWhere(['IN', $fieldName, $statusIDs]);
        }
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
        return $IDs;
    }


}