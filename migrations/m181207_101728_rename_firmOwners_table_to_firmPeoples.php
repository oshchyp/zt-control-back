<?php

use yii\db\Migration;

/**
 * Class m181207_101728_rename_firmOwners_table_to_firmPeoples
 */
class m181207_101728_rename_firmOwners_table_to_firmPeoples extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('firmOwners', 'firmPeoples');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('firmPeoples', 'firmOwners');
    }


}
