<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 26.10.2018
 * Time: 13:02
 */

namespace app\modules\api1\controllers\actions;

use yii\rest\Action;

/**
 * Trait RestBaseMethods
 * @package app\modules\api\controllers\actions
 * @mixin Action
 */
trait RestBaseMethods
{

    public $responseProcessing;

    public function run ($id=null) {
        $data = parent::run($id);
        if ($this -> responseProcessing!==null){
            return call_user_func($this -> responseProcessing,  $data, $this->id,$this);
        } else {
            return $data;
        }
    }

}