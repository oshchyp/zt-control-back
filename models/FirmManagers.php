<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 12.12.2018
 * <<<<<<< HEAD
 * Time: 10:20
 * =======
 * Time: 12:45
 * >>>>>>> refactor
 */

namespace app\models;


use app\components\behaviors\EstablishUID;

class FirmManagers extends Users
{

    public $type = 2;

    /**
     * @param string $email
     */
    public function setEmail($email=''){
        $this->mail = $email;
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->mail;
    }

    public function setName($name=''){
        $nameExplode = explode(' ',$name);
    }

    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone'], 'unique'],
            [['uid', 'name', 'phone', 'email'], 'string', 'max' => 255]
        ];
    }

    public function fields(){
        return ['uid', 'name', 'phone', 'email'];
    }

    public function behaviors()
    {
        return [
            'establishUID' => [
                'class' => EstablishUID::className()
            ],
        ];
    }

    public static function find(){
        return parent::find()->where(['type'=>2]);
    }


}