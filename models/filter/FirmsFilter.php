<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.10.2018
 * Time: 10:22
 */

namespace app\models\filter;


use app\models\Firms;
use yii\helpers\ArrayHelper;

class FirmsFilter extends Firms
{

    use FilterTrait;

    /**
     * @return array
     */
    public function rulesFilterModel()
    {
        return [
            [['square|range:>'], 'number'],
            [['name', 'rdpu', 'regionUID', 'pointUID', 'nearElevatorUID','region.name','point.name'], 'string', 'max' => 250],
            ['sender', 'in', 'range' => ArrayHelper::getColumn(static::distributionStatuses(), 'id')],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['region.name','point.name','square|range:>']);
    }

    /**
     * @return array
     */
    public function fieldsSearchAll()
    {
        return ['name', 'rdpu','region.name','point.name'];
    }

    /**
     * @return array
     */
    public function fieldsSearchIndividual()
    {
        return $this->fieldsSearchAll();
    }

    /**
     * @return array
     */
    public function fieldsComparisonMore()
    {
        return ['square'];
    }

    /**
     * @return array
     */
    public function fieldsEquating()
    {
        return ['regionUID', 'pointUID','sender'];
    }

}