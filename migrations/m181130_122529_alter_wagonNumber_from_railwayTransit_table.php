<?php

use yii\db\Migration;

/**
 * Class m181130_122529_alter_wagonNumber_from_railwayTransit_table
 */
class m181130_122529_alter_wagonNumber_from_railwayTransit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('railwayTransit', 'wagonNumber', $this->integer()->defaultValue(0));
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181130_122529_alter_wagonNumber_from_railwayTransit_table cannot be reverted.\n";

        return false;
    }


}
