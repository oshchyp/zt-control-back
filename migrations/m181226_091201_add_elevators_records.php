<?php

use yii\db\Migration;

/**
 * Class m181226_091201_add_elevators_records
 */
class m181226_091201_add_elevators_records extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $elevators = [
            [
                'name' => 'Сарата',
                'bit' => 2
            ],
            [
                'name' => 'Хмельхник',
                'bit' => 4
            ],
        ];
        foreach ($elevators as $item){
            $this->insert('zlataElevators',$item);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('zlataElevators');
        return false;
    }
}
