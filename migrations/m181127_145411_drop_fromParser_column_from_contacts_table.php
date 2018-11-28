<?php

use yii\db\Migration;

/**
 * Handles dropping fromParser from table `contacts`.
 */
class m181127_145411_drop_fromParser_column_from_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('contacts', 'fromParser');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('contacts', 'fromParser', $this->integer()->notNull());
    }
}
