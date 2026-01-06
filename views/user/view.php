<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode($user->username);
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="card mb-4">
        <div class="card-body">
            <h2><?= Html::encode($user->username) ?></h2>
            <p class="text-muted">Email: <?= Html::encode($user->email) ?></p>
            <p class="text-muted">Постов: <?= count($posts) ?></p>
            <p class="text-muted">Подписчиков: <?= count($user->followers) ?></p>
            <p class="text-muted">Подписок: <?= count($user->following) ?></p>
            
            <?php if (Yii::$app->user->id && Yii::$app->user->id != $user->id): ?>
                <?php if ($isFollowing): ?>
                    <?= Html::a('Отписаться', ['unfollow', 'id' => $user->id], [
                        'class' => 'btn btn-secondary',
                        'data' => ['method' => 'post']
                    ]) ?>
                <?php else: ?>
                    <?= Html::a('Подписаться', ['follow', 'id' => $user->id], [
                        'class' => 'btn btn-primary',
                        'data' => ['method' => 'post']
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <h3>Посты пользователя</h3>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">
            У пользователя пока нет постов.
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <h4 class="post-title">
                    <?= Html::a(Html::encode($post->title), ['post/view', 'id' => $post->id]) ?>
                </h4>
                <div class="post-meta">
                    <span><?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>
                    <?php if ($post->is_request_only): ?>
                        <span class="badge badge-warning ml-2">Только по запросу</span>
                    <?php endif; ?>
                    <?php if (!$post->is_public): ?>
                        <span class="badge badge-secondary ml-2">Приватный</span>
                    <?php endif; ?>
                </div>
                <div class="post-content">
                    <?= nl2br(Html::encode(mb_substr($post->content, 0, 300))) ?>
                    <?= mb_strlen($post->content) > 300 ? '...' : '' ?>
                </div>
                <?php if ($post->tags): ?>
                    <div class="tags">
                        <?php foreach ($post->tags as $tag): ?>
                            <?= Html::a($tag->name, ['post/index', 'tag' => $tag->name], ['class' => 'badge badge-primary tag-badge']) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                    <?= Html::a('Читать далее', ['post/view', 'id' => $post->id], ['class' => 'btn btn-primary btn-sm']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


