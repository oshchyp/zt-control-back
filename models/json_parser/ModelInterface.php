<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.08.2018
 * Time: 13:30
 */

namespace app\models\json_parser;


interface ModelInterface
{

    public function loadModel();

    public static function getInstance($data);
}