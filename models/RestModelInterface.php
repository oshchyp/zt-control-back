<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 17.09.2018
 * Time: 12:09
 */

namespace app\models;


interface RestModelInterface
{

    public static function getAllInstances();

    public static function getUidAttrName();

    public function setUid();

    public function getRestValidators();

}