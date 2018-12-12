<?php

use yii\db\Migration;

/**
 * Handles the creation of table `farmCultures`.
 */
class m181210_073209_create_farmCultures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('farmCultures', [
            'id' => $this->primaryKey(),
            'farmUID' => $this->string(250),
            'cultureUID' => $this->string(250),
            'square' => $this->decimal(9,2),
            'yield' => $this->decimal(5,2)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('farmCultures');
    }
}
