<?php

use yii\db\Migration;

/**
 * Class m220616_104848_addColumnToUser
 */
class m220616_104848_addColumnToUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\UserShop::tableName(), 'tg_chat_id', $this->integer());
        $this->addColumn(\app\models\UserShop::tableName(), 'tg_username', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(\app\models\UserShop::tableName(), 'tg_chat_id');
        $this->dropColumn(\app\models\UserShop::tableName(), 'tg_username');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220616_104848_addColumnToUser cannot be reverted.\n";

        return false;
    }
    */
}
