<?php

use yii\db\Migration;

/**
 * Handles adding type to table `firmPeoples`.
 */
class m181207_102037_add_type_column_to_firmPeoples_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('firmPeoples', 'type', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('firmPeoples', 'type');
    }
}
