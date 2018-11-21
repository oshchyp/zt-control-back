<?php

use yii\db\Migration;

/**
 * Handles dropping processedSquare from table `firms`.
 */
class m181121_135207_drop_processedSquare_column_from_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('firms', 'processedSquare');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('firms', 'processedSquare', $this->float());
    }
}
