<?php

use yii\db\Migration;

/**
 * Handles adding admin to table `users`.
 */
class m181206_095958_add_admin_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'admin', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'admin');
    }
}
