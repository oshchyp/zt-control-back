<?php

use yii\db\Migration;

class m180920_145010_create_table_firmCultures extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%firmCultures}}', [
            'id' => $this->primaryKey(),
            'firmUID' => $this->string()->notNull(),
            'cultureUID' => $this->string()->notNull(),
            'square' => $this->float()->notNull(),
            'weight' => $this->float()->notNull(),
            'year' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%firmCultures}}');
    }
}
