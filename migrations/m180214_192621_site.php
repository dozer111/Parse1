<?php

use yii\db\Migration;

/**
 * Class m180214_192621_site
 */
class m180214_192621_site extends Migration
{

  /*  public function safeUp()
    {

    }


    public function safeDown()
    {
        echo "m180214_192621_site cannot be reverted.\n";

        return false;
    }*/


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('site',[
            'id'=>$this->primaryKey()->unsigned(),
            'site_url'=>$this->string()->notNull()->unique()
        ]);
        $this->alterColumn('site','id',$this->integer(11)." NOT NULL AUTO_INCREMENT");

    }

    public function down()
    {
        #echo "m180214_192621_site cannot be reverted.\n";
        $this->dropTable('site');
        return false;
    }

}
