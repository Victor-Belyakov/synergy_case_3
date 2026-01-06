<?php

use yii\db\Migration;

class m200101_000005_create_comment_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-comment-post_id', '{{%comment}}', 'post_id');
        $this->createIndex('idx-comment-user_id', '{{%comment}}', 'user_id');
        $this->addForeignKey('fk-comment-post_id', '{{%comment}}', 'post_id', '{{%post}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-comment-user_id', '{{%comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-comment-user_id', '{{%comment}}');
        $this->dropForeignKey('fk-comment-post_id', '{{%comment}}');
        $this->dropTable('{{%comment}}');
    }
}


