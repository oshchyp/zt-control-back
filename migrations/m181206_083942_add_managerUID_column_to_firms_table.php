<?php

use yii\db\Migration;

/**
 * Handles adding managerUID to table `firms`.
 */
class m181206_083942_add_managerUID_column_to_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('firms', 'managerUID', $this->string(250));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('firms', 'managerUID');
    }
}
