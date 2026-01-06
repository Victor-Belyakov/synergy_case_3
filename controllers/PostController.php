<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Post;
use app\models\Comment;
use app\models\Tag;
use app\models\User;

class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'my-posts'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete', 'my-posts'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($tag = null)
    {
        $query = Post::find()
            ->joinWith('user')
            ->joinWith('tags')
            ->orderBy(['created_at' => SORT_DESC]);

        if ($tag) {
            $query->andWhere(['{{%tag}}.name' => $tag]);
        }

        $userId = Yii::$app->user->id;
        
        if ($userId) {
            $user = User::findOne($userId);
            $followingIds = array_map(function($u) { return $u->id; }, $user->following);
            $followingIds[] = $userId;
            
            $query->andWhere(['or',
                ['and', ['is_public' => true], ['is_request_only' => false]],
                ['user_id' => $followingIds]
            ]);
        } else {
            $query->andWhere(['and', ['is_public' => true], ['is_request_only' => false]]);
        }

        $posts = $query->all();
        
        $filteredPosts = [];
        foreach ($posts as $post) {
            if ($post->canView($userId)) {
                $filteredPosts[] = $post;
            }
        }

        $tags = Tag::find()->orderBy(['name' => SORT_ASC])->all();

        return $this->render('index', [
            'posts' => $filteredPosts,
            'tags' => $tags,
            'selectedTag' => $tag,
        ]);
    }

    public function actionView($id)
    {
        $post = $this->findModel($id);
        $userId = Yii::$app->user->id;

        if (!$post->canView($userId)) {
            throw new NotFoundHttpException('Пост недоступен для просмотра.');
        }

        $comment = new Comment();
        if ($comment->load(Yii::$app->request->post()) && Yii::$app->user->id) {
            $comment->post_id = $post->id;
            $comment->user_id = Yii::$app->user->id;
            if ($comment->save()) {
                Yii::$app->session->setFlash('success', 'Комментарий добавлен.');
                return $this->refresh();
            }
        }

        return $this->render('view', [
            'post' => $post,
            'comment' => $comment,
        ]);
    }

    public function actionCreate()
    {
        $model = new Post();
        $model->user_id = Yii::$app->user->id;
        $model->is_public = true;
        $model->is_request_only = false;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Пост успешно создан.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('У вас нет прав для редактирования этого поста.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Пост успешно обновлен.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('У вас нет прав для удаления этого поста.');
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Пост успешно удален.');
        return $this->redirect(['my-posts']);
    }

    public function actionMyPosts()
    {
        $posts = Post::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('my-posts', [
            'posts' => $posts,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пост не найден.');
    }
}

