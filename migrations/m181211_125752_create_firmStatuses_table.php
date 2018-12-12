  <?php

use yii\db\Migration;

/**
 * Handles the creation of table `firmStatuses`.
 */
class m181211_125752_create_firmStatuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('firmStatuses', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)
        ]);


        $insertArr = [
            'Новый','Ключевой', 'Активный', 'Пассивный', 'Старый', 'Перспективный', 'Рисковый', 'Чёрный'
        ];

        foreach ($insertArr as $name){
            $model = (new \yii\db\Query())->from('firmStatuses')->where(['name' => $name])->one();
            if (!$model){
                $this->insert('firmStatuses',['name'=>$name]);
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('firmStatuses');
    }
}
