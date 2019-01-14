<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.01.2019
 * Time: 13:19
 */

namespace app\models\firmManagers;


use app\components\models\EstablishRelation;
use app\models\asrelation\FirmsAsRelation;
use app\models\zlataElevators\ZlataElevatorFinder;

trait FirmManagersRelations
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirms()
    {
        return EstablishRelation::hasMany($this,FirmsAsRelation::instance(),['managerUID'=>'uid']);
    }

    /**
     * @return \app\models\ZlataElevators[]|array
     */
    public function getElevators(){
        return ZlataElevatorFinder::findElevatorsByBit($this->elevatorBit);
    }

    /**
     * @return \app\models\ZlataElevators[]|array
     */
    public function getElevatorsView(){
        return ZlataElevatorFinder::findElevatorsByBit($this->elevatorViewBit);
    }

}