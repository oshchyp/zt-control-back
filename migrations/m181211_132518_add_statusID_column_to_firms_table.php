<?php

use yii\db\Migration;

/**
 * Handles adding statusID to table `firms`.
 */
class m181211_132518_add_statusID_column_to_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('firms', 'statusID', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('firms', 'statusID');
    }
}
