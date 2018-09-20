<?php

use yii\db\Migration;

class m180920_145010_create_table_elevators extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%elevators}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%elevators}}');
    }
}
