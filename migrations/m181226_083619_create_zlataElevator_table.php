<?php

use yii\db\Migration;

/**
 * Handles the creation of table `zlataElevator`.
 */
class m181226_083619_create_zlataElevator_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('zlataElevators', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250),
            'bit' => $this->integer()->unique()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('zlataElevators');
    }
}
