<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Tag extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tag}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
        ];
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])
            ->viaTable('{{%post_tag}}', ['tag_id' => 'id']);
    }
}


