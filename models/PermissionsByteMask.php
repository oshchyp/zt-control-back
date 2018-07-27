<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 23.07.2018
 * Time: 15:53
 */

namespace app\models;


class PermissionsByteMask
{
    public static function getPermissions()
    {

        return [


           //0 level
            [
                'firms' => [
                    'description' => 'Контрагенты',
                    'parent' => '',
                ],
                'logistics' => [
                    'description' => 'Логистика',
                    'parent' => '',
                ],
                'contracts' => [
                    'description' => 'Контракты',
                    'parent' => '',
                ],
                'railroad' => [
                    'description' => 'Железная дорога',
                    'parent' => '',
                ],
                'settings' => [
                    'description' => 'Настройки',
                    'parent' => '',
                ],
            ],

            //1 level
            [
                'logistics-users' => [
                    'description' => 'Пользователи',
                    'parent' => 'logistics',
                ],
                'users' => [
                    'description' => 'Пользователи',
                    'parent' => 'settings',
                ],
                'elevators' => [
                    'description' => 'Элеватор',
                    'parent' => 'logistics',
                ],
                'roads-history' => [
                    'description' => 'История',
                    'parent' => 'logistics',
                ],

                'railroad-roads' => [
                    'description' => 'Маршруты',
                    'parent' => 'railroad',
                ],
                'railroad-history' => [
                    'description' => 'История',
                    'parent' => 'railroad',
                ],
                'railroad-contracts' => [
                    'description' => 'Контракты',
                    'parent' => 'railroad',
                ],
            ],

            //2 level
           // [],


        ];

    }

}