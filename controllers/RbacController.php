<?php

namespace app\controllers;

use app\models\Permissions;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\web\Controller;

class RbacController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'ips' => ['94.74.94.127']
                ]
            ],
            'denyCallback' => function(){
                die('Access is denied');
            }
        ];
        return $behaviors;
    }

    public function actionInit()
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
        if ($assignments){
            foreach ($assignments as $v){
                $userRole = Yii::$app->authManager->createRole($v->roleName);
                Yii::$app->authManager->assign($userRole, $v -> userId);
            }
        }
    }

    public function actionAdd_role()
    {
        $userRole = Yii::$app->authManager->createRole('admin');
        Yii::$app->authManager->assign($userRole, 1);
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
