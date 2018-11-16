<?php

use yii\db\Migration;

/**
 * Handles adding processedSquare to table `firms`.
 */
class m181116_104140_add_processedSquare_column_to_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('firms', 'processedSquare', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('firms', 'processedSquare');
    }
}
