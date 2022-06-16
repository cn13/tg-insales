<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_shop}}`.
 */
class m220616_083034_createUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_shop}}', [
            'id' => $this->primaryKey(),
            'shop' => $this->string()->notNull(),
            'token' => $this->string()->notNull(),
            'insales_id' => $this->string()->notNull(),
            'access_token' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_shop}}');
    }
}
