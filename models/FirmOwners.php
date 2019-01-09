<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 12.12.2018
<<<<<<< HEAD
 * Time: 10:19
=======
 * Time: 12:43
>>>>>>> refactor
 */

namespace app\models;


class FirmOwners extends FirmPeoples
{
    public static $typeInFinder = 1;

    public static function find(){
        return parent::find()->where(['type'=>1]);
    }
}