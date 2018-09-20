<?php

use yii\db\Migration;

class m180920_145010_create_table_firmDistances extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%firmDistances}}', [
            'id' => $this->primaryKey(),
            'firmUID' => $this->string()->notNull(),
            'pointUID' => $this->string()->notNull(),
            'distance' => $this->float()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%firmDistances}}');
    }
}
