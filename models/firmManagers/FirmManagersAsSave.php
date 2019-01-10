<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.01.2019
 * Time: 12:48
 */

namespace app\models\firmManagers;


use app\components\behaviors\EstablishUID;
use app\components\behaviors\PhoneHandling;
use app\components\models\ModelAsResourceInterface;
use app\models\zlataElevators\ZlataElevatorRelationsForModels;
use app\models\zlataElevators\ZlataElevatorSaveToModel;

class FirmManagersAsSave extends \app\models\FirmManagers implements ModelAsResourceInterface
{

    use FirmManagersRelations;
    use ZlataElevatorRelationsForModels;
    use ZlataElevatorSaveToModel;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'establishUID' => [
                'class' => EstablishUID::className()
            ],
            'phoneHandling' => [
                'class' => PhoneHandling::className()
            ],
            'managerRole' => [
                'class' => EstablishManagerRoleBehavior::className()
            ]
        ];
    }

}