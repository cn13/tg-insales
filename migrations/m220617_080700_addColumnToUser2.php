<?php

use yii\db\Migration;

/**
 * Class m220617_080700_addColumnToUser2
 */
class m220617_080700_addColumnToUser2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\UserShop::tableName(), 'email', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(\app\models\UserShop::tableName(), 'email');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220617_080700_addColumnToUser2 cannot be reverted.\n";

        return false;
    }
    */
}
