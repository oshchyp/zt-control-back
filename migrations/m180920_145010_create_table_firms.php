<?php

use yii\db\Migration;

class m180920_145010_create_table_firms extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%firms}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'rdpu' => $this->string(),
            'square' => $this->float(),
            'regionUID' => $this->string(),
            'pointUID' => $this->string(),
            'nearElevatorUID' => $this->string()->notNull(),
            'sender' => $this->tinyInteger()->notNull(),
            'test' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%firms}}');
    }
}
