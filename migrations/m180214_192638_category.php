<?php

use yii\db\Migration;

/**
 * Class m180214_192638_category
 */
class m180214_192638_category extends Migration
{
    /**
     * @inheritdoc
     */
   /* public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m180214_192638_category cannot be reverted.\n";

        return false;
    }*/


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('category',
            [
                'id'=>$this->primaryKey()->unsigned(),
                'category_url'=>$this->string()->notNull()->unique(),
                'site_id'=>$this->integer()
            ]);
        $this->alterColumn('category','id',$this->integer(11)." NOT NULL AUTO_INCREMENT");
        $this->addForeignKey('category_site',
                'category','site_id',
                'site','id',
                'CASCADE'
            );
    }

    public function down()
    {
        #        echo "m180214_192638_category cannot be reverted.\n";
        $this->dropTable('category');
        return false;
    }

}
