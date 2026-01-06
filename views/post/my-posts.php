<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-my-posts">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">
            У вас пока нет постов. <?= Html::a('Создайте первый пост', ['create']) ?>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <h3 class="post-title">
                    <?= Html::a(Html::encode($post->title), ['view', 'id' => $post->id]) ?>
                </h3>
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
                    <?= nl2br(Html::encode(mb_substr($post->content, 0, 200))) ?>
                    <?= mb_strlen($post->content) > 200 ? '...' : '' ?>
                </div>
                <?php if ($post->tags): ?>
                    <div class="tags">
                        <?php foreach ($post->tags as $tag): ?>
                            <?= Html::a($tag->name, ['index', 'tag' => $tag->name], ['class' => 'badge badge-primary tag-badge']) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
                    <?= Html::a('Просмотр', ['view', 'id' => $post->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= Html::a('Редактировать', ['update', 'id' => $post->id], ['class' => 'btn btn-warning btn-sm']) ?>
                    <?= Html::a('Удалить', ['delete', 'id' => $post->id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить этот пост?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


