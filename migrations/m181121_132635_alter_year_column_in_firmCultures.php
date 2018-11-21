<?php

use yii\db\Migration;

/**
 * Class m181121_132635_alter_year_column_in_firmCultures
 */
class m181121_132635_alter_year_column_in_firmCultures extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('firmCultures', 'year', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //  $this->dropColumn('contacts', 'main');
    }
}
