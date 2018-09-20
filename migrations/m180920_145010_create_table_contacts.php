<?php

use yii\db\Migration;

class m180920_145010_create_table_contacts extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%contacts}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string(),
            'postID' => $this->integer(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'fromParser' => $this->string()->notNull(),
            'firmUID' => $this->string(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%contacts}}');
    }
}
