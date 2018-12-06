<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 06.12.2018
 * Time: 12:09
 */

namespace app\models\migrations;

class EstablishAdmin extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->updateAdminField(1);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->updateAdminField(0);
    }

    public function updateAdminField($adminFieldValue = 1){
        foreach ($this->adminConditions() as $condition){
            $this->update('users',['admin'=>$adminFieldValue],$condition);
        }
    }

    public function adminConditions(){
        return [
            ['phone'=>'380634206192'],
            ['phone'=>'380992345593'],
           // ['id'=>34]
        ];
    }

}