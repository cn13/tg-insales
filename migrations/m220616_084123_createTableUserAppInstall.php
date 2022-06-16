<?php

use yii\db\Migration;

/**
 * Class m220616_084123_createTableUserAppInstall
 */
class m220616_084123_createTableUserAppInstall extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_app_install}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'install_at' => $this->timestamp()->defaultExpression('NOW()'),
            'uninstall_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_app_install}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220616_084123_createTableUserAppInstall cannot be reverted.\n";

        return false;
    }
    */
}
