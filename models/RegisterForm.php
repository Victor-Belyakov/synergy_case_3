<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_repeat'], 'required'],
            [['username', 'email'], 'trim'],
            ['email', 'email'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Это имя пользователя уже занято.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже используется.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
        ];
    }

    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->password = $this->password;
            $user->setScenario('register');
            
            if ($user->save()) {
                return true;
            }
        }
        return false;
    }
}


