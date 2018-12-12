<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 12:18
 */

namespace app\components\migrations;

use app\models\Permissions;
use app\modules\api\models\Users;
use Yii;
use yii\db\Query;
use yii\rbac\Assignment;

class RBACGenerator extends Migration
{

    public function safeUp()
    {
        $assignments = $this->getAssignments();
        Yii::$app->authManager->removeAllPermissions();
        Yii::$app->authManager->removeAllRoles();
        $roleStructure = Permissions::roleStructure();
        $this -> createRole();
        $this -> createPermissions();
        $this -> setChildRole($roleStructure);
        $this -> setPermToRole();
        $this -> createAdmin();
        $this->saveAssignments($assignments);
        $this->createAdminUsers();
    }

    public function safeDown()
    {
       // return false;
    }

    public function createAdminUsers(){
        $adminUsers = Users::find()->where(['admin'=>1])->all();
        if ($adminUsers){
            foreach ($adminUsers as $userModel){
                (new \yii\db\Query())->createCommand()->delete('auth_assignment',['user_id'=>$userModel->id])->execute();
                $userRole = Yii::$app->authManager->createRole('admin');
                Yii::$app->authManager->assign($userRole, $userModel->id);
            }
        }
    }

    public function saveAssignments($assignments){
        if ($assignments){
            foreach ($assignments as $v){
                $userRole = Yii::$app->authManager->createRole($v->roleName);
                Yii::$app->authManager->assign($userRole, $v -> userId);
            }
        }
    }

    public function createRole(){
        $roles = Permissions::getRoles();

        foreach ($roles as $k=>$v){
            $roleInfo = Yii::$app->authManager->createRole($k);
            $roleInfo->description = $v['description'];
            Yii::$app->authManager->add($roleInfo);
        }
    }

    public function createPermissions(){
        $perm = Permissions::getPermissions();

        foreach ($perm as $k=>$v){
            $roleInfo = Yii::$app->authManager->createPermission($k);
            $roleInfo->description = $v['description'];
            Yii::$app->authManager->add($roleInfo);
        }
    }

    public function setChildRole($roleStructure=[]){
        foreach ($roleStructure as $k=>$v){
            if ($v){
                foreach ($v as $k1=>$v1){
                    $parent = Yii::$app->authManager->createRole($k);
                    $child = Yii::$app->authManager->createRole($k1);
                    Yii::$app->authManager->addChild($parent, $child);
                    if ($v1){
                        $this->setChildRole($v);
                    }
                }
            }
        }
    }

    public function setPermToRole(){
        $permToRole = Permissions::permissionsToRole();
        foreach ($permToRole as $role=>$permissions){
            foreach ($permissions as $v){
                $roleObj = Yii::$app->authManager->createRole($role);
                $permObj =  Yii::$app->authManager->createPermission($v);
                Yii::$app->authManager->addChild($roleObj, $permObj);
            }
        }
    }

    public function createAdmin(){
        $structure = Permissions::roleStructure();
        $admin = Yii::$app->authManager->createRole('admin');
        Yii::$app->authManager->add($admin);
        foreach ($structure as $k=>$v){
            $role = Yii::$app->authManager->createRole($k);
            Yii::$app->authManager->addChild($admin,$role);
        }
    }

    public function getAssignments()
    {

        $query = (new Query())
            ->from( Yii::$app->authManager->assignmentTable);

        $assignments = [];
        foreach ($query->all( Yii::$app->authManager->db) as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
            ]);
        }

        return $assignments;
    }

}