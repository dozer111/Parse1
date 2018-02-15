<?php

use yii\db\Migration;

/**
 * Class m180214_214819_record_info
 */
class m180214_214819_record_info extends Migration
{ /*
    public function safeUp()
    {

    }


    public function safeDown()
    {
        echo "m180214_192749_record_info cannot be reverted.\n";

        return false;
    }*/


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('record_info',[
            'id'=>$this->primaryKey()->unsigned(),
            'site_id'=>$this->integer()->notNull(),
            'category_id'=>$this->integer()->notNull(),
            'time_parsed'=>$this->string()->notNull(),
            'record_count'=>$this->integer()->notNull(),
            'record_full_string_id'=>$this->integer(11)
        ]);
        $this->alterColumn('record_info','id',$this->integer(11)." NOT NULL AUTO_INCREMENT");
        $this->addForeignKey('short_all',
            'record_info','record_full_string_id',
            'record_data','id',
            'CASCADE'
        );
    }

    public function down()
    {
        #echo "m180214_192749_record_info cannot be reverted.\n";
        $this->dropTable('record_info');
        return false;
    }
}
