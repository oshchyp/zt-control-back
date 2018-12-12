<?php

use yii\db\Migration;

/**
 * Class m181212_144059_alter_mail_column_usres_table
 */
class m181212_144059_alter_mail_column_usres_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'mail', $this->string(250)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('users', 'mail', $this->string(250));
    }

}
