<?php

use yii\db\Migration;

class m200101_000006_create_subscription_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'follower_id' => $this->integer()->notNull(),
            'following_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-subscription-follower_id', '{{%subscription}}', 'follower_id');
        $this->createIndex('idx-subscription-following_id', '{{%subscription}}', 'following_id');
        $this->createIndex('uq-subscription-follower-following', '{{%subscription}}', ['follower_id', 'following_id'], true);
        $this->addForeignKey('fk-subscription-follower_id', '{{%subscription}}', 'follower_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-subscription-following_id', '{{%subscription}}', 'following_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-subscription-following_id', '{{%subscription}}');
        $this->dropForeignKey('fk-subscription-follower_id', '{{%subscription}}');
        $this->dropTable('{{%subscription}}');
    }
}

