<?php

use yii\db\Migration;

/**
 * Handles adding mainCultureUID to table `firms`.
 */
class m181116_104451_add_mainCultureUID_column_to_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('firms', 'mainCultureUID', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('firms', 'mainCultureUID');
    }
}
