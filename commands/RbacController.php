<?php
/**
 * @see http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

function dump($var, $kill = false)
{
    if (YII_DEBUG) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        if ($kill) {
            die();
        }
    }
}

class RbacController extends Controller
{
    private $_rolesLogistics = [
        ///////// logistics
        'logistics' => [
           'type' => 'role',
           'id' => 'logistics',
           'description' => 'Логистика',
       ],
       ///////// logistics users
       'logistics/users' => [
           'type' => 'role',
           'id' => 'logistics/users',
           'description' => 'Логистика | Пользователи',
       ],
       ///////// logistics users perm
       'logistics/users/view' => [
           'type' => 'perm',
           'id' => 'logistics/users/view',
           'description' => 'Логистика | Пользователи | Просмотр',
       ],
       'logistics/users/create' => [
           'type' => 'perm',
           'id' => 'logistics/users/create',
           'description' => 'Логистика | Пользователи | Создание',
       ],
       'logistics/users/update' => [
           'type' => 'perm',
           'id' => 'logistics/users/update',
           'description' => 'Логистика | Пользователи | Обновление',
       ],
       'logistics/users/delete' => [
           'type' => 'perm',
           'id' => 'logistics/users/delete',
           'description' => 'Логистика | Пользователи | Удаление',
       ],
       ///////// logistics elevators
       'logistics/elevators' => [
           'type' => 'role',
           'id' => 'logistics/elevators',
           'description' => 'Логистика | Элеватор',
       ],
       ///////// logistics elevators perm
       'logistics/elevators/view' => [
           'type' => 'perm',
           'id' => 'logistics/elevators/view',
           'description' => 'Логистика | Элеватор | Просмотр',
       ],
       'logistics/elevators/create' => [
           'type' => 'perm',
           'id' => 'logistics/elevators/create',
           'description' => 'Логистика | Элеватор | Создание',
       ],
       'logistics/elevators/update' => [
           'type' => 'perm',
           'id' => 'logistics/elevators/update',
           'description' => 'Логистика | Элеватор | Обновление',
       ],
       'logistics/elevators/delete' => [
           'type' => 'perm',
           'id' => 'logistics/elevators/delete',
           'description' => 'Логистика | Элеватор | Удаление',
       ],

        ///////// logistics history
        'logistics/history' => [
           'type' => 'role',
           'id' => 'logistics/history',
           'description' => 'Логистика | История',
        ],
   ];

    public function actionInit()
    {
        Yii::$app->authManager->removeAll();
        $this->_createPermAll();
        $this->_createAdmin();
    }

    public function actionAdd_role()
    {
        $userRole = Yii::$app->authManager->getRole('admin');
        Yii::$app->authManager->assign($userRole, 1);
    }

    public function actionDebug()
    {
        // Yii::$app->authManager->removeAll();
        // $this->_createPerm('logistics/elevators/update');
        // ec
    }

    private function _createAdmin()
    {
        if (!$admin = Yii::$app->authManager->getPermission('admin')) {
            $admin = Yii::$app->authManager->createRole('admin');
            $admin->description = 'Верховнейший админ';
            Yii::$app->authManager->add($admin);
        }

        if ($admin) {
            foreach ($this->_permRules() as $k => $v) {
                if (count(explode('/', $k)) == 1) {
                    Yii::$app->authManager->addChild($admin, $this->_getPerm($k));
                }
            }
        }
    }

    private function _createPermAll()
    {
        foreach ($this->_permRules() as $k => $v) {
            $this->_createPerm($k);
        }
    }

    private function _permRules()
    {
        return $this->_rolesLogistics;
    }

    private function _createPerm($id)
    {
        $method = ArrayHelper::getValue($this->_permRules(), $id.'.type') == 'role' ? 'createRole' : 'createPermission';
        $createPerm = Yii::$app->authManager->$method($id);
        $createPerm->description = ArrayHelper::getValue($this->_permRules(), $id.'.description');
        Yii::$app->authManager->add($createPerm);

        $idArr = explode('/', $id);
        array_pop($idArr);
        $parentID = implode('/', $idArr);

        if ($parentID) {
            if (!$parentObj = $this->_getPerm($parentID)) {
                $this->_createPerm($parentID);
                $parentObj = $this->_getPerm($parentID);
            }

            if ($parentObj) {
                Yii::$app->authManager->addChild($parentObj, $this->_getPerm($id));
            }
        }
    }

    private function _getPerm($id)
    {
        $method = ArrayHelper::getValue($this->_permRules(), $id.'.type') == 'role' ? 'getRole' : 'getPermission';

        return Yii::$app->authManager->$method($id);
    }
}
