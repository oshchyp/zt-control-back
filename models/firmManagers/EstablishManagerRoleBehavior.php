<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.01.2019
 * Time: 12:39
 */

namespace app\models\firmManagers;


use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class EstablishManagerRoleBehavior extends Behavior
{


    public $roles = ['firms' => 'firms','farms'=>'farms'];

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }

    public function afterInsert($event)
    {
        if ($this->roles) {
            Yii::$app->authManager->db->createCommand()
                ->delete(Yii::$app->authManager->assignmentTable, ['user_id' => $this->owner->id])
                ->execute();
            foreach ($this->roles as $item) {
                $roleObj = Yii::$app->authManager->createRole($item);
                Yii::$app->authManager->assign($roleObj, $this->owner->id);
            }
        }
    }

}