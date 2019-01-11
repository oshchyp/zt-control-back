<?php

use yii\db\Migration;

/**
 * Class m190111_105425_correct_name_hmelnik
 */
class m190111_105425_correct_name_hmelnik extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('zlataElevators',['name'=>'Хмельник'],'name="Хмельхник"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->update('zlataElevators',['name'=>'Хмельхник'],'name="Хмельник"');
    }

}
