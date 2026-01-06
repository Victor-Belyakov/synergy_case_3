<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Subscription;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['follow', 'unfollow'],
                'rules' => [
                    [
                        'actions' => ['follow', 'unfollow'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'follow' => ['POST'],
                    'unfollow' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $users = User::find()->orderBy(['username' => SORT_ASC])->all();
        $currentUserId = Yii::$app->user->id;

        return $this->render('index', [
            'users' => $users,
            'currentUserId' => $currentUserId,
        ]);
    }

    public function actionView($id)
    {
        $user = $this->findModel($id);
        $currentUserId = Yii::$app->user->id;
        
        $posts = $user->posts;
        $visiblePosts = [];
        foreach ($posts as $post) {
            if ($post->canView($currentUserId)) {
                $visiblePosts[] = $post;
            }
        }

        $isFollowing = false;
        if ($currentUserId) {
            $currentUser = User::findOne($currentUserId);
            $isFollowing = $currentUser && $currentUser->isFollowing($user->id);
        }

        return $this->render('view', [
            'user' => $user,
            'posts' => $visiblePosts,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function actionFollow($id)
    {
        $currentUserId = Yii::$app->user->id;
        
        if ($currentUserId == $id) {
            Yii::$app->session->setFlash('error', 'Нельзя подписаться на самого себя.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $subscription = Subscription::findOne([
            'follower_id' => $currentUserId,
            'following_id' => $id,
        ]);

        if (!$subscription) {
            $subscription = new Subscription();
            $subscription->follower_id = $currentUserId;
            $subscription->following_id = $id;
            if ($subscription->save()) {
                Yii::$app->session->setFlash('success', 'Вы подписались на пользователя.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при подписке.');
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionUnfollow($id)
    {
        $currentUserId = Yii::$app->user->id;
        
        $subscription = Subscription::findOne([
            'follower_id' => $currentUserId,
            'following_id' => $id,
        ]);

        if ($subscription && $subscription->delete()) {
            Yii::$app->session->setFlash('success', 'Вы отписались от пользователя.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пользователь не найден.');
    }
}

