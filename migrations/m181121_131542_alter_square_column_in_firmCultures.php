<?php

use yii\db\Migration;

/**
 * Class m181121_131542_alter_square_column_in_firmCultures
 */
class m181121_131542_alter_square_column_in_firmCultures extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('firmCultures', 'square', $this->float()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      //  $this->dropColumn('contacts', 'main');
    }
}
