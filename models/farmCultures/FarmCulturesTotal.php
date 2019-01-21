<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.01.2019
 * Time: 09:20
 */

namespace app\models\farmCultures;


use yii\db\ActiveQuery;

class FarmCulturesTotal
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
    public function __construct(ActiveQuery $query, $firmTableAlias = '`farms`.')
    {
        $this->query = clone $query;
        if (!$this->existJoin('farmCultures AS cultures')){
            $this->getQuery()->leftJoin('farmCultures AS cultures','`cultures`.`farmUID`='.$firmTableAlias.'`uid`');
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



}