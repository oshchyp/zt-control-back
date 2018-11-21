<?php

use yii\db\Migration;

/**
 * Class m181121_132323_alter_weight_column_in_firmCultures
 */
class m181121_132323_alter_weight_column_in_firmCultures extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('firmCultures', 'weight', $this->float()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //  $this->dropColumn('contacts', 'main');
    }
}
