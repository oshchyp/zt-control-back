<?php

namespace app\models;

/**
 * This is the model class for table "permissions".
 *
 * @property int $id
 * @property int $userID
 * @property string $resource
 * @property int $createP
 * @property int $updateP
 * @property int $viewP
 * @property int $deleteP
 */
class Permissions extends \yii\db\ActiveRecord
{
    public static function getPermissions()
    {
        return [
            ///////// logistics users perm
            'logistics-users/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'logistics-users/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'logistics-users/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],
            'logistics-users/delete' => [
                'description' => 'Удаление',
                'convertName' => 'delete'
            ],

            'users/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'users/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'users/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],
            'users/delete' => [
                'description' => 'Удаление',
                'convertName' => 'delete'
            ],

            'elevators/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'elevators/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'elevators/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],
            'elevators/delete' => [
                'description' => 'Удаление',
                'convertName' => 'delete'
            ],
            'roads-history/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'roads-history/statistics' => [
                'description' => 'Статистика',
                'convertName' => 'statistics'
            ],
            'roads/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'firms/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'firms/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'firms/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],

            'farms/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'farms/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'farms/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],

            'farms/delete' => [
                'description' => 'Удаление',
                'convertName' => 'delete'
            ],

            'firm-owners/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'firm-owners/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'firm-owners/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],
            'firm-owners/delete' => [
                'description' => 'Удаление',
                'convertName' => 'delete'
            ],

            'firm-managers/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'firm-managers/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'firm-managers/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],
            'firm-managers/delete' => [
                'description' => 'Удаление',
                'convertName' => 'delete'
            ],

            'contracts/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],


            'railroad-roads/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'railroad-roads/update' => [
                'description' => 'Обновление',
                'convertName' => 'update'
            ],
            'railroad-roads/create' => [
                'description' => 'Создание',
                'convertName' => 'create'
            ],
            'railroad-roads/delete' => [
                'description' => 'Удаление',
                'convertName' => 'delete'
            ],


            'railroad-history/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ],
            'railroad-contracts/view' => [
                'description' => 'Просмотр',
                'convertName' => 'view'
            ]
        ];
    }

    public static function getRoles()
    {
        return [
            'firms-all' => [
                'description' => 'Контрагенты',
            ],
            'firms' => [
                'description' => 'Фирмы',
            ],
            'firm-owners' => [
                'description' => 'Владельцы контрагентов',
            ],

            'farms' => [
                'description' => 'Хозяйства',
            ],

            'firm-managers' => [
                'description' => 'Менеджеры контрагентов',
            ],

            'logistics' => [
                'description' => 'Логистика',
            ],
            'contracts' => [
                'description' => 'Контракты',
            ],
            'railroad' => [
                'description' => 'Железная дорога',
            ],
            'settings' => [
                'description' => 'Настройки',
            ],
            'logistics-users' => [
                'description' => 'Пользователи',
            ],
            'users' => [
                'description' => 'Пользователи',
            ],
            'elevators' => [
                'description' => 'Элеватор',
            ],
            'roads-history' => [
                'description' => 'История',
            ],
            'roads' => [
                'description' => 'Маршруты',
            ],

            'railroad-roads' => [
                'description' => 'Маршруты',
            ],
            'railroad-history' => [
                'description' => 'История',
            ],
            'railroad-contracts' => [
                'description' => 'Контракты',
            ],
        ];
    }

    public static function roleStructure()
    {
        return [
            'firms-all' => [
                'firms' => [],
                'firm-owners' => [],
                'firm-managers' => [],
                'farms' => []
            ],
            'logistics' => [
                'elevators' => [],
                'logistics-users' => [],
                'roads' => [],
                'roads-history' => [],
            ],
            'contracts' => [
            ],
            'railroad' => [
                'railroad-roads' => [],
                'railroad-history' => [],
                'railroad-contracts' => []
            ],
            'settings' => [
                'users' => [],
            ],
        ];
    }

    public static function permissionsToRole()
    {
        return [
            'logistics-users' => [
                'logistics-users/view', 'logistics-users/update', 'logistics-users/create', 'logistics-users/delete',
            ],
            'users' => [
                'users/view', 'users/update', 'users/create', 'users/delete',
            ],
            'elevators' => [
                'elevators/view', 'elevators/update', 'elevators/create', 'elevators/delete',
            ],
            'roads-history' => [
                'roads-history/view','roads-history/statistics',
            ],
            'roads' => [
                'roads/view',
            ],
            'railroad-roads' => [
                'railroad-roads/view','railroad-roads/update','railroad-roads/create','railroad-roads/delete',
            ],
            'railroad-history' => [
                'railroad-history/view',
            ],
            'railroad-contracts' => [
                'railroad-contracts/view',
            ],
            'firms' => [
                'firms/view','firms/update','firms/create',
            ],
            'farms' => [
                'farms/view','farms/update','farms/create','farms/delete'
            ],
            'firm-owners' => [
                'firm-owners/view','firm-owners/update','firm-owners/create','firm-owners/delete'
            ],
            'firm-managers' => [
                'firm-managers/view','firm-managers/update','firm-managers/create','firm-managers/delete'
            ],
            'contracts' => [
                'contracts/view',
            ],
        ];
    }

    public static function permissionPath($perm)
    {
        $permissions = static::permissionsToRole();
        $rolesPath = static::rolesPath();
        $permRole = null;
        $permPath = [];
        foreach ($permissions as $k => $v) {
            if (in_array($perm, $v)) {
                $permRole = $k;
                break;
            }
        }

        if (isset($rolesPath[$permRole])) {
            $permPath = $rolesPath[$permRole];
        }
        //  $permPath[] = $perm;
        return $permPath;
    }


    public static function rolesPath(&$result = [], $parent = [], $roleStructure = [])
    {
        if (!$roleStructure) {
            $roleStructure = static::roleStructure();
        }
        foreach ($roleStructure as $k => $v) {
            $per = $parent;
            $per[] = $k;
            if (!$v) {
                $result[$k] = $per;
            } else {
                static::rolesPath($result, $per, $v);
            }
        }
        return $result;
    }

    public static function getListRoles(&$result = [], $parent = '', $roleStructure = [], $level=0)
    {
        if (!$roleStructure) {
            $roleStructure = static::roleStructure();
        }
        $permissionsToRole = static::permissionsToRole();
        foreach ($roleStructure as $key => $value) {
            $result[] = static::createRoleInfo($key,['key' => $key,'parent' => $parent, 'level' => $level]);
            if ($value) {
                static::getListRoles($result, $key, $value,$level+1);
            } else if (isset($permissionsToRole[$key])) {
                foreach ($permissionsToRole[$key] as $perm){
                    $result[] = static::createPermissionInfo($perm,['key' => $perm,'parent' => $key, 'level' => $level+1]);
                }
            }
        }
        return $result;
    }

    public static function createRoleInfo($key,$addInfo=[]){
        $roles = static::getRoles();
        return $addInfo + $roles[$key];
    }

    public static function createPermissionInfo($key,$addInfo=[]){
        $permissions = static::getPermissions();
        return $addInfo + $permissions[$key];
    }


}
