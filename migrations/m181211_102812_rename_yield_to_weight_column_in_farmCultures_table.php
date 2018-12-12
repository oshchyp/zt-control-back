<?php

use yii\db\Migration;

/**
 * Class m181211_102812_rename_yield_to_weight_column_in_farmCultures_table
 */
class m181211_102812_rename_yield_to_weight_column_in_farmCultures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('farmCultures','yield','weight');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('farmCultures','weight','yield');
    }

}
