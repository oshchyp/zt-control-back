<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.01.2019
 * Time: 09:29
 */

namespace app\models\farmCultures;


use app\components\models\ModelAsExtraDataInterface;
use yii\db\ActiveQuery;

class FarmCulturesTotalAsExtraData extends FarmCulturesTotal implements ModelAsExtraDataInterface
{

//    protected $farmCulturesTotalInstance;
//
//    /**
//     * @return mixed
//     */
//    public function getFarmCulturesTotalInstance()
//    {
//        return $this->farmCulturesTotalInstance;
//    }
//
//
//
//
//    /**
//     * FirmCulturesTotal constructor.
//     * @param ActiveQuery $query
//     * @param string $firmTableAlias
//     */
//    public function __construct(ActiveQuery $query, $firmTableAlias = '`farms`.')
//    {
//        $this->farmCulturesTotalInstance = new FarmCulturesTotal($query);
//    }
//
//
//    public static function find(): ActiveQuery
//    {
//        return $this->getQuery()
//    }
//
//    /**
//     * @return array
//     */
//    public static function relations()
//    {
//        return [];
//    }
    public static function find(): ActiveQuery
    {
        // TODO: Implement find() method.
    }

    /**
     * @return array
     */
    public static function relations()
    {
        // TODO: Implement relations() method.
    }
}