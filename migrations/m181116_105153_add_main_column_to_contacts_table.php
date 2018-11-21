<?php

use yii\db\Migration;

/**
 * Handles adding main to table `contacts`.
 */
class m181116_105153_add_main_column_to_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

         $this->addColumn('contacts', 'main', $this->tinyInteger(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('contacts', 'main');
    }
}
