<?php

use yii\db\Migration;

/**
 * Class m181212_140643_alter_classesExist_column_from_culture_table
 */
class m181212_133800_alter_classesExist_column_from_culture_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('culture', 'classesExist', $this->tinyInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('culture', 'classesExist', $this->tinyInteger());
        return false;
    }

}
