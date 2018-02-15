<?php

use yii\db\Migration;

/**
 * Class m180214_214801_record_data
 */
class m180214_214801_record_data extends Migration
{

    /*   public function safeUp()
       {

       }


       public function safeDown()
       {
           echo "m180214_192801_record_data cannot be reverted.\n";

           return false;
       }*/


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('record_data',[
            'id'=>$this->primaryKey()->unsigned(),
            'record_json_data'=>$this->text()
        ]);
        $this->alterColumn('record_data','id',$this->integer(11)." NOT NULL AUTO_INCREMENT");
    }

    public function down()
    {
        #  echo "m180214_192801_record_data cannot be reverted.\n";
        $this->dropTable('record_data');
        return false;
    }

}
