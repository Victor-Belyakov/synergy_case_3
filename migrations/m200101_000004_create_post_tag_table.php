<?php

use yii\db\Migration;

class m200101_000004_create_post_tag_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%post_tag}}', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-post_tag', '{{%post_tag}}', ['post_id', 'tag_id']);
        $this->createIndex('idx-post_tag-post_id', '{{%post_tag}}', 'post_id');
        $this->createIndex('idx-post_tag-tag_id', '{{%post_tag}}', 'tag_id');
        $this->addForeignKey('fk-post_tag-post_id', '{{%post_tag}}', 'post_id', '{{%post}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-post_tag-tag_id', '{{%post_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-post_tag-tag_id', '{{%post_tag}}');
        $this->dropForeignKey('fk-post_tag-post_id', '{{%post_tag}}');
        $this->dropTable('{{%post_tag}}');
    }
}


