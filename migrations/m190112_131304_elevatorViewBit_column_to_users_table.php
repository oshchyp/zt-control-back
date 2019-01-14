<?php

use yii\db\Migration;

/**
 * Class m190112_131304_elevatorViewBit_column_to_users_table
 */
class m190112_131304_elevatorViewBit_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'elevatorViewBit', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'elevatorViewBit');
    }
}
