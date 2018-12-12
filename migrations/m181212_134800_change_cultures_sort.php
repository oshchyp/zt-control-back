<?php

use yii\db\Migration;

/**
 * Class m181212_134800_change_cultures_sort
 */
class m181212_134800_change_cultures_sort extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $cultures = [
            ['Пшеница','3314c918-ebc0-11e7-8660-60a44cafafcb',1],
            ['Ячмень','48f9e143-f6ca-11e7-aa53-6466b304e311',0],
            ['Кукуруза','2cfcb900-ebc0-11e7-8660-60a44cafafcb',0],
            ['Подсолнечник','97506d2a-f6c9-11e7-aa53-6466b304e311',0],
            ['Рапс','22301bb8-699f-11e8-aad6-6466b304e311',0],
            ['Соя','0e2d2bb5-f6ca-11e7-aa53-6466b304e311',0],
            ['Бобовые', null,0],
            ['Сорго', null,0],
            ['Нут', null,0],
            ['Лён', null,0],
            ['Просо', null,0],

        ];

        $this->delete('culture');
        foreach ($cultures as $item){
            [$name,$uid,$classesExist] = $item;
            if (!$uid){
                $uid = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
            }
            $this->insert('culture',['name'=>$name,'uid'=>$uid,'classesExist'=>$classesExist]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
