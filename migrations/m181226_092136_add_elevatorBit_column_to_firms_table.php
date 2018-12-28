<?php

use yii\db\Migration;

/**
 * Handles adding elevatorBit to table `firms`.
 */
class m181226_092136_add_elevatorBit_column_to_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('firms', 'elevatorBit', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('firms', 'elevatorBit');
    }
}
