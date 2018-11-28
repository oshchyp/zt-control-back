<?php

use yii\db\Migration;

/**
 * Class m181127_132749_alter_typeID_column_from_railwayTransit_table
 */
class m181127_132749_alter_typeID_column_from_railwayTransit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('railwayTransit', 'typeID', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('railwayTransit', 'typeID', $this->integer()->notNull());

        return false;
    }

}
