<?php

use yii\db\Migration;

class m180920_145010_create_table_users extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string()->notNull()->defaultValue(''),
            'lastName' => $this->string()->notNull()->defaultValue(''),
            'phone' => $this->char()->notNull(),
            'token' => $this->string(),
            'code' => $this->integer()->notNull()->defaultValue('0'),
            'mail' => $this->string()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%users}}');
    }
}
