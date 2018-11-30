<?php

use yii\db\Migration;

/**
 * Class m181129_134914_alter_column_nearElevatorUID_from_firms_table
 */
class m181129_134914_alter_column_nearElevatorUID_from_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('firms', 'nearElevatorUID', $this->string(250)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('firms', 'nearElevatorUID', $this->string(250)->notNull());
    }
}
