<?php

use yii\db\Migration;

/**
 * Handles the creation of table `farms`.
 */
class m181210_072358_create_farms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('farms', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(250)->unique(),
            'name' => $this->string(250),
            'regionUID' => $this->string(250),
            'pointUID' => $this->string(250),
            'square' => $this->decimal(9,2),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('farms');
    }
}
