<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements IdentityInterface
{
    public $password;

    public static function tableName()
    {
        return '{{%user}}';
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
            [['username', 'email', 'password'], 'required', 'on' => 'register'],
            [['username', 'email'], 'required', 'on' => 'update'],
            [['username'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['password'], 'string', 'min' => 6, 'on' => 'register'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password' => 'Пароль',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            if ($this->password) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, ['user_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    public function getFollowers()
    {
        return $this->hasMany(User::class, ['id' => 'follower_id'])
            ->viaTable('{{%subscription}}', ['following_id' => 'id']);
    }

    public function getFollowing()
    {
        return $this->hasMany(User::class, ['id' => 'following_id'])
            ->viaTable('{{%subscription}}', ['follower_id' => 'id']);
    }

    public function isFollowing($userId)
    {
        return Subscription::find()
            ->where(['follower_id' => $this->id, 'following_id' => $userId])
            ->exists();
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
}

