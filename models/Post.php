<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Post extends ActiveRecord
{
    public $tagNames;

    public static function tableName()
    {
        return '{{%post}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['is_public', 'is_request_only'], 'boolean'],
            [['user_id'], 'integer'],
            [['tagNames'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Автор',
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'is_public' => 'Публичный',
            'is_request_only' => 'Только по запросу',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'tagNames' => 'Теги',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable('{{%post_tag}}', ['post_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->tagNames = implode(', ', array_map(function($tag) {
            return $tag->name;
        }, $this->tags));
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if ($this->tagNames) {
            $tagNames = array_map('trim', explode(',', $this->tagNames));
            $tagIds = [];
            
            foreach ($tagNames as $tagName) {
                if (empty($tagName)) continue;
                
                $tag = Tag::findOne(['name' => $tagName]);
                if (!$tag) {
                    $tag = new Tag();
                    $tag->name = $tagName;
                    $tag->save();
                }
                $tagIds[] = $tag->id;
            }
            
            $this->unlinkAll('tags', true);
            foreach ($tagIds as $tagId) {
                $tag = Tag::findOne($tagId);
                $this->link('tags', $tag);
            }
        } else {
            $this->unlinkAll('tags', true);
        }
    }

    public function canView($userId = null)
    {
        if ($this->is_public && !$this->is_request_only) {
            return true;
        }
        
        if ($userId && $this->user_id == $userId) {
            return true;
        }
        
        if ($this->is_request_only && $userId) {
            $user = User::findOne($userId);
            return $user && $user->isFollowing($this->user_id);
        }
        
        return false;
    }
}


