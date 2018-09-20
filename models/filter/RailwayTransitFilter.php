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

    public $executorFirm;

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


    public $different;

    public $datePlane;

    public $dateArrival;


    public $status;

    public $ownershipWagon;


    public $stringForSearchAll;

    public function init()
    {
        $query = RailwayTransit::find()->with(RailwayTransit::relations());
        $this->setQuery($query);
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
            'customerFirm' => 'customerFirms.name',
            'executorFirm' => 'executorFirm.name',
            'wagonNumber',
            'consignmentNumber',
            'weight',
            'unloadingWeight',
            'destinationStation' => 'destinationStation.name',
            'departureStation' => 'departureStation.name',
            'product' => 'product.name',
            'forwarderFirm' => 'forwarderFirm.name',
            'class',
            'loadingWeight',
            'price',
            'additionalPrice',
            'tariff',
            'contract' => 'contract.name',
            'different' => '(loadingWeight - unloadingWeight)',
            'datePlane' => 'FROM_UNIXTIME (`datePlane`,"%Y.%m.%d")',
            'dateArrival' => 'FROM_UNIXTIME (`dateArrival`,"%Y.%m.%d")',
            'status',
            'ownershipWagon'
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
            'customerFirm', 'executorFirm', 'destinationStation', 'departureStation', 'product', 'forwarderFirm', 'contract'
        ];
    }

    /**
     *
     */
    public function statusSearchAll()
    {
        $this->searchAttrByItems(RailwayTransit::statuses(), $this->stringForSearchAll(), 'statusID');
    }

    /**
     *
     */
    public function statusSearchIndividual()
    {
        $this->searchAttrByItems(RailwayTransit::statuses(), $this->status, 'statusID');
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
        $this->searchAttrByItems(RailwayTransit::ownershipWagons(), $this->ownershipWagon, 'ownershipWagonID');
    }

    /**
     * @param $items
     * @param $string
     * @param $fieldName
     * @param string $nameAttr
     * @param string $idAttr
     */
    private function searchAttrByItems($items, $string, $fieldName, $nameAttr = 'name', $idAttr = 'id')
    {
        if ($string) {
            $statusIDs = static::_findIdsByNameFromItems($items, $string, $nameAttr, $idAttr);
            $this->getQuery()->orWhere(['IN', $fieldName, $statusIDs]);
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