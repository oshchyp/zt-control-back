<?php

use yii\db\Migration;

/**
 * Class m181129_135147_alter_column_test_from_firms_table
 */
class m181129_135147_alter_column_test_from_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('firms', 'test', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('firms', 'test', $this->integer()->notNull());
    }
}
