<?php

use yii\db\Migration;

/**
 * Handles adding yieldForecastInside to table `farmCultures`.
 */
class m190114_133218_add_yieldForecastInside_column_to_farmCultures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('farmCultures', 'yieldForecastInside', $this->float()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('farmCultures', 'yieldForecastInside');
    }
}
