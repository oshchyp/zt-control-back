<?php

use yii\db\Migration;

/**
 * Handles the creation of table `regionCultures`.
 */
class m181119_141623_create_regionCultures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('regionCultures', [
            'id' => $this->primaryKey(),
            'cultureUID' => $this->string(),
            'regionUID' => $this->string(),
            'weight' => $this->float(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('regionCultures');
    }
}
