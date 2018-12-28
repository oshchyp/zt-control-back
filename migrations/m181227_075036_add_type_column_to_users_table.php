<?php

use yii\db\Migration;

/**
 * Handles adding type to table `users`.
 */
class m181227_075036_add_type_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'type', $this->integer()->after('admin')->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'type');
    }
}
