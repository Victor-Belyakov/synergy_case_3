<?php

use yii\db\Migration;

class m200101_000003_create_tag_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%tag}}');
    }
}


