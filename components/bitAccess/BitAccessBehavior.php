<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 12.01.2019
 * Time: 15:22
 */

namespace app\components\bitAccess;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class BitAccessBehavior extends Behavior
{

    public $userBit;

    public $resourceBit;

    public $errorForbidden = 'This object is forbidden to edit';

    public $errorAllowed = 'This bit is not allowed to assign this object.';

    /**
     * @return mixed
     */
    public function getUserBit()
    {
        if ($this->userBit === null){
            $this->userBit = \Yii::$app->user ? \Yii::$app->user->identity->elevatorBit : null;
        }
        return $this->userBit;
    }

    /**
     * @return int
     */
    public function getResourceBitOld()
    {
        return $this->owner->getResourceBitOld();
    }

    /**
     * @return int
     */
    public function getResourceBitNew(){
        return $this->owner->getResourceBitNew();
    }


    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeValidate',
        ];
    }

    public function beforeValidate($event)
    {

        $oldValueIsValid = $this->getResourceBitOld() & $this -> getUserBit();
        $newValueIsValid = $this->getResourceBitNew() & $this -> getUserBit();

        if (!$oldValueIsValid){
            $event->isValid = $oldValueIsValid;
            $this->owner->addError('permission', $this->errorForbidden);
        } else if (!$newValueIsValid){
            $event->isValid = $newValueIsValid;
            $this->owner->addError('permission', $this->errorAllowed);
        }

    }


}