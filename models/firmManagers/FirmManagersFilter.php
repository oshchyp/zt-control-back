<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 15:32
 */

namespace app\models\firmManagers;


use app\components\models\EstablishRelation;
use app\models\asrelation\FirmsAsRelation;
use app\modules\api\models\FirmPeoplesFilter;

class FirmManagersFilter extends FirmPeoplesFilter
{

    public function afterSearch()
    {
      //  dump($this->getQuery()); die();
    }

}