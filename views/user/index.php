<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (empty($users)): ?>
        <div class="alert alert-info">
            Пользователи не найдены.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($users as $user): ?>
                <div class="col-md-6">
                    <div class="user-card">
                        <h4><?= Html::a(Html::encode($user->username), ['view', 'id' => $user->id]) ?></h4>
                        <p class="text-muted">Email: <?= Html::encode($user->email) ?></p>
                        <p class="text-muted">Постов: <?= count($user->posts) ?></p>
                        <?php if ($currentUserId && $currentUserId != $user->id): ?>
                            <?php
                            $currentUser = \app\models\User::findOne($currentUserId);
                            $isFollowing = $currentUser && $currentUser->isFollowing($user->id);
                            ?>
                            <?php if ($isFollowing): ?>
                                <?= Html::a('Отписаться', ['unfollow', 'id' => $user->id], [
                                    'class' => 'btn btn-secondary btn-sm',
                                    'data' => ['method' => 'post']
                                ]) ?>
                            <?php else: ?>
                                <?= Html::a('Подписаться', ['follow', 'id' => $user->id], [
                                    'class' => 'btn btn-primary btn-sm',
                                    'data' => ['method' => 'post']
                                ]) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

