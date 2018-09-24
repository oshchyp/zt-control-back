<?php

use yii\db\Migration;

class m180920_145010_create_table_contracts extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%contracts}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'firmUID' => $this->string()->notNull(),
            'cultureUID' => $this->string()->notNull(),
            'regionUID' => $this->string()->notNull(),
            'receiverPointUID' => $this->string()->notNull(),
            'amount' => $this->float()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%contracts}}');
    }
}