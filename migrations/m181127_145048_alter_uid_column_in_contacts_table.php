<?php

use yii\db\Migration;

/**
 * Class m181127_145048_alter_uid_column_in_contacts_table
 */
class m181127_145048_alter_uid_column_in_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('contacts', 'uid', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('contacts', 'uid', $this->string()->notNull());

        return false;
    }
}
