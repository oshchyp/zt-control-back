<?php

use yii\db\Migration;

/**
 * Class m190115_081802_alter_phone_field_in_users_table
 */
class m190115_081802_alter_phone_field_in_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users','phone',$this->char(12)->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('users','phone',$this->char(12));
    }
}
