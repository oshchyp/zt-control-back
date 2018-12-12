<?php

use yii\db\Migration;

/**
 * Class m181211_113413_alter_weight_column_from_farmCultures_table
 */
class m181211_113413_alter_weight_column_from_farmCultures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('farmCultures','weight',$this->decimal(9,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('farmCultures','weight',$this->decimal(5,2));
    }
}
