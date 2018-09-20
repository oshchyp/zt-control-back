<?php

use yii\db\Migration;

class m180920_145010_create_table_regions extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%regions}}', [
            'id' => $this->integer()->notNull(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('id_2', '{{%regions}}', 'id');
        $this->createIndex('id', '{{%regions}}', 'id', true);
    }

    public function down()
    {
        $this->dropTable('{{%regions}}');
    }
}
