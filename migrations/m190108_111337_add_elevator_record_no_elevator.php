<?php

use yii\db\Migration;

/**
 * Class m181226_091201_add_elevators_records
 */
class m190108_111337_add_elevator_record_no_elevator extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $elevators = [
            [
                'name' => 'Нет элеватора',
                'bit' => 1
            ]
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
