<?php

use yii\db\Migration;

/**
 * Handles adding elevatorBit to table `users`.
 */
class m181226_091051_add_elevatorBit_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'elevatorBit', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'elevatorBit');
    }
}
