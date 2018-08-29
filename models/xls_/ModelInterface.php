<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.08.2018
 * Time: 13:30
 */

namespace app\models\xls;


interface ModelInterface
{

    public function loadModel($parser);

    public static function getInstance($data);
}