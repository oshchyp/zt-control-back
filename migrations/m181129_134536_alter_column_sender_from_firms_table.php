<?php

use yii\db\Migration;

/**
 * Class m181129_134536_alter_column_sender_from_firms_table
 */
class m181129_134536_alter_column_sender_from_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('firms', 'sender', $this->tinyInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('firms', 'sender', $this->tinyInteger()->notNull());
    }

}
