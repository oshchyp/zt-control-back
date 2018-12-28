<?php

use yii\db\Migration;

/**
 * Handles adding uid to table `users`.
 */
class m181227_080421_add_uid_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'uid', $this->string()->null()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'uid');
    }
}
