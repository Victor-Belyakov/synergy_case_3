<?php

use yii\db\Migration;

class m200101_000002_create_post_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
            'is_public' => $this->boolean()->defaultValue(true),
            'is_request_only' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-post-user_id', '{{%post}}', 'user_id');
        $this->addForeignKey('fk-post-user_id', '{{%post}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-post-user_id', '{{%post}}');
        $this->dropTable('{{%post}}');
    }
}


