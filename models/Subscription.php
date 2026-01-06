<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Subscription extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%subscription}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['follower_id', 'following_id'], 'required'],
            [['follower_id', 'following_id'], 'integer'],
            [['follower_id', 'following_id'], 'unique', 'targetAttribute' => ['follower_id', 'following_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'follower_id' => 'Подписчик',
            'following_id' => 'Подписка',
            'created_at' => 'Дата подписки',
        ];
    }

    public function getFollower()
    {
        return $this->hasOne(User::class, ['id' => 'follower_id']);
    }

    public function getFollowing()
    {
        return $this->hasOne(User::class, ['id' => 'following_id']);
    }
}


