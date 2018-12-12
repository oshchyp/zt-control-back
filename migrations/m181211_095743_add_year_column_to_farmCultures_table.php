<?php

use yii\db\Migration;

/**
 * Handles adding year to table `farmCultures`.
 */
class m181211_095743_add_year_column_to_farmCultures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('farmCultures', 'year', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('farmCultures', 'year');
    }
}
