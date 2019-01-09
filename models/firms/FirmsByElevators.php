<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.01.2019
 * Time: 16:23
 */

namespace app\models\firms;


class FirmsByElevators extends Firms
{

    use FirmsByElevatorsTrait;

    /**
     * @return array
     */
    public function fields()
    {
        return array_merge(parent::fields(),['elevator']);
    }

    public static function relations()
    {
        return array_merge(parent::relations(),['elevator']);
    }

}