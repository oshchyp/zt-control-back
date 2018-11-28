<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 28.11.2018
 * Time: 16:09
 */

namespace app\modules\api\models;


use yii\db\ActiveQuery;

class FirmCulturesTotal
{

    /**
     * @var ActiveQuery
     */
    protected $query;

    /**
     * @return ActiveQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * FirmCulturesTotal constructor.
     * @param ActiveQuery $query
     * @param string $firmTableAlias
     */
    public function __construct(ActiveQuery $query, $firmTableAlias = 'firms.')
    {
        $this->query = clone $query;
        $this->getQuery()->leftJoin('firmCultures AS firmCulturesTotal','firmCulturesTotal.firmUID = '.$firmTableAlias.'uid AND firmCulturesTotal.year = '.date('Y'));
    }


    /**
     * @return float|int
     */
    public function weight(){
        return $this->_summHundling($this->query->sum('(firmCulturesTotal.weight*firmCulturesTotal.square)'));
    }

    /**
     * @return float|int
     */
    public function square(){
        return $this->_summHundling($this->query->sum('firmCulturesTotal.square'));
    }

    /**
     * @param int $summ
     * @return float|int
     */
    private function _summHundling($summ=0){
        return $summ ? (float)$summ : 0;
    }

}