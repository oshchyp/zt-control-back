<?php

use yii\db\Migration;

/**
 * Handles adding yieldLastYear to table `farmCultures`.
 */
class m190114_125514_add_yieldLastYear_column_to_farmCultures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('farmCultures', 'yieldLastYear', $this->float()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('farmCultures', 'yieldLastYear');
    }
}
