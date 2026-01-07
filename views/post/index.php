<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Главная';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Теги</h5>
                </div>
                <div class="card-body">
                    <?php if ($tags): ?>
                        <div class="list-group">
                            <a href="<?= Url::to(['post/index']) ?>" class="list-group-item list-group-item-action <?= !$selectedTag ? 'active' : '' ?>">
                                Все посты
                            </a>
                            <?php foreach ($tagsWithCount as $tagData): ?>
                                <?php if ($tagData['count'] > 0): ?>
                                    <a href="<?= Url::to(['post/index', 'tag' => $tagData['tag']->name]) ?>" 
                                       class="list-group-item list-group-item-action <?= $selectedTag == $tagData['tag']->name ? 'active' : '' ?>">
                                        <?= Html::encode($tagData['tag']->name) ?>
                                        <span class="badge badge-primary badge-pill float-right">
                                            <?= $tagData['count'] ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Теги отсутствуют</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <?php if (empty($posts)): ?>
                <div class="alert alert-info">
                    Посты не найдены.
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <h3 class="post-title">
                            <?= Html::a(Html::encode($post->title), ['view', 'id' => $post->id]) ?>
                        </h3>
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
                        <div class="post-content">
                            <?= nl2br(Html::encode(mb_substr($post->content, 0, 300))) ?>
                            <?= mb_strlen($post->content) > 300 ? '...' : '' ?>
                        </div>
                        <?php if ($post->tags): ?>
                            <div class="tags">
                                <?php foreach ($post->tags as $tag): ?>
                                    <?= Html::a($tag->name, ['index', 'tag' => $tag->name], ['class' => 'badge badge-primary tag-badge']) ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="mt-3">
                            <?= Html::a('Читать далее', ['view', 'id' => $post->id], ['class' => 'btn btn-primary btn-sm']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

