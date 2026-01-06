<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Html::encode($post->title);
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">
    <div class="post-card">
        <h1 class="post-title"><?= Html::encode($post->title) ?></h1>
        <div class="post-meta">
            <span>Автор: <?= Html::a(Html::encode($post->user->username), ['user/view', 'id' => $post->user_id]) ?></span>
            <span class="ml-3"><?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>
            <?php if ($post->is_request_only): ?>
                <span class="badge badge-warning ml-2">Только по запросу</span>
            <?php endif; ?>
            <?php if (!$post->is_public): ?>
                <span class="badge badge-secondary ml-2">Приватный</span>
            <?php endif; ?>
        </div>
        <?php if ($post->tags): ?>
            <div class="tags mb-3">
                <?php foreach ($post->tags as $tag): ?>
                    <?= Html::a($tag->name, ['index', 'tag' => $tag->name], ['class' => 'badge badge-primary tag-badge']) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="post-content">
            <?= nl2br(Html::encode($post->content)) ?>
        </div>
        <?php if (Yii::$app->user->id == $post->user_id): ?>
            <div class="mt-3">
                <?= Html::a('Редактировать', ['update', 'id' => $post->id], ['class' => 'btn btn-warning']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $post->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот пост?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <h3>Комментарии (<?= count($post->comments) ?>)</h3>

    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="card mb-4">
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($comment, 'content')->textarea(['rows' => 3, 'placeholder' => 'Напишите комментарий...']) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?= Html::a('Войдите', ['site/login']) ?> чтобы оставить комментарий.
        </div>
    <?php endif; ?>

    <div class="comments">
        <?php if (empty($post->comments)): ?>
            <div class="alert alert-info">Комментариев пока нет.</div>
        <?php else: ?>
            <?php foreach ($post->comments as $comment): ?>
                <div class="comment">
                    <div class="comment-author">
                        <?= Html::encode($comment->user->username) ?>
                    </div>
                    <div class="comment-date">
                        <?= Yii::$app->formatter->asDatetime($comment->created_at) ?>
                    </div>
                    <div class="comment-content mt-2">
                        <?= nl2br(Html::encode($comment->content)) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>


