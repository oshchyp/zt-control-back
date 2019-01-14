<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 13:11
 */

namespace app\models\firmManagers;


use app\components\models\ModelAsResourceInterface;
use app\models\zlataElevators\ZlataElevatorRelationsForModels;

class FirmManagers extends \app\models\FirmManagers implements ModelAsResourceInterface
{

    use FirmManagersRelations;

    /**
     * @return array
     */
    public function fields(){
        return ['uid', 'name', 'phone', 'email','id','firms','elevators','elevatorsView'];
    }

    public static function relations()
    {
        return ['firms'];
    }
}