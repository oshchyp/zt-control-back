<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 16:04
 */

namespace app\models\asrelation;

use app\components\models\ModelAsRelationInterface;
use app\models\FirmOwners;
use app\models\FirmPeoples;

class FirmOwnersAsRelation extends  FirmPeoples implements ModelAsRelationInterface
{
    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }

  //  public static fin
}