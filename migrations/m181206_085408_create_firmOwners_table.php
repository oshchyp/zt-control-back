<?php

use yii\db\Migration;

/**
 * Handles the creation of table `firmOwners`.
 */
class m181206_085408_create_firmOwners_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('firmOwners', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(250)->unique(),
            'name' => $this->string(250),
            'phone' => $this->string(250),
            'email' => $this->string(250)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('firmOwners');
    }
}
