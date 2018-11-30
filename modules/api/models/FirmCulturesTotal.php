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
    public function __construct(ActiveQuery $query, $firmTableAlias = '`firms`.')
    {
        $this->query = clone $query;
        if (!$this->existJoin('firmCultures AS cultures')){
            $this->getQuery()->leftJoin('firmCultures AS cultures','`cultures`.`firmUID`='.$firmTableAlias.'`uid`');
        }
        $this->getQuery()->andFilterWhere(['cultures.year'=>date('Y')]);
       // $this->getQuery()->leftJoin('firmCultures AS firmCulturesTotal','firmCulturesTotal.firmUID = '.$firmTableAlias.'uid AND firmCulturesTotal.year = '.date('Y'));
    }

    public function existJoin($join){
        if ($this->getQuery()->join){
            foreach ($this->getQuery()->join as $j){
                if (in_array($join,$j)){
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * @return float
     * @throws \yii\db\Exception
     */
    public function weight(){
       return $this->sum('cultures.weight*cultures.square');
    }


    /**
     * @return float
     * @throws \yii\db\Exception
     */
    public function square(){

        return $this->sum('cultures.square');
    }

    /**
     * @param $field
     * @return float
     * @throws \yii\db\Exception
     */
    public function sum($field){

        return (float)$this->query->select(['SUM('.$field.')'])->createCommand()->queryScalar();
    }

    /**
     * @param int $summ
     * @return float|int
     */
    private function _summHundling($summ=0){
        return $summ ? (float)$summ : 0;
    }
    /*
     * SELECT `firms`.* FROM `firms` LEFT JOIN `firmCultures` `cultures` ON cultures.firmUID = firms.uid LEFT JOIN `culture` `culture` ON culture.uid = cultures.cultureUID
     * LEFT JOIN `firmCultures` `firmCulturesTotal` ON firmCulturesTotal.firmUID = firms.uid AND firmCulturesTotal.year = 2018
     * WHERE `firms`.`id`=11158 ORDER BY `id` DESC
     */

    /*
     *SELECT SUM(firmCulturesTotal1.square) FROM `firms` LEFT JOIN `firmCultures` `cultures` ON cultures.firmUID = firms.uid
     * LEFT JOIN `culture` `culture` ON culture.uid = cultures.cultureUID
     * LEFT JOIN `firmCultures` `firmCulturesTotal` ON firmCulturesTotal.firmUID = firms.uid AND firmCulturesTotal.year = 2018
     * WHERE `firms`.`id`=11158
     */

}