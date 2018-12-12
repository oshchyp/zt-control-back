<?php

use yii\db\Migration;

/**
 * Handles adding firmUID to table `farms`.
 */
class m181210_080325_add_firmUID_column_to_farms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('farms', 'firmUID', $this->string(250));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('farms', 'firmUID');
    }
}
