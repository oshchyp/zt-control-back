<?php

use yii\db\Migration;

/**
 * Handles adding ownerUID to table `firms`.
 */
class m181206_084757_add_ownerUID_column_to_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('firms', 'ownerUID', $this->string(250));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('firms', 'ownerUID');
    }
}
