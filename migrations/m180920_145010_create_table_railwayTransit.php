<?php

use yii\db\Migration;

class m180920_145010_create_table_railwayTransit extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%railwayTransit}}', [
            'id' => $this->integer()->notNull(),
            'uid' => $this->string(),
            'customerFirmUID' => $this->string(),
            'executorFirmUID' => $this->string(),
            'ownershipWagonID' => $this->integer(),
            'wagonNumber' => $this->integer()->notNull(),
            'consignmentNumber' => $this->integer()->notNull(),
            'weight' => $this->float(),
            'loadingWeight' => $this->float(),
            'unloadingWeight' => $this->float(),
            'datePlane' => $this->integer(),
            'destinationStationUID' => $this->string(),
            'departureStationUID' => $this->string(),
            'productUID' => $this->string(),
            'forwarderFirmUID' => $this->string(),
            'classID' => $this->integer(),
            'class' => $this->string(),
            'dateArrival' => $this->integer(),
            'price' => $this->float(),
            'tariff' => $this->float(),
            'additionalPrice' => $this->float()->comment('Дополнительные расходы'),
            'contractUID' => $this->string(),
            'statusID' => $this->integer()->notNull()->defaultValue('0'),
            'addInfo' => $this->text(),
        ], $tableOptions);

        $this->createIndex('id', '{{%railwayTransit}}', 'id');
        $this->createIndex('id_2', '{{%railwayTransit}}', 'id', true);
    }

    public function down()
    {
        $this->dropTable('{{%railwayTransit}}');
    }
}
