<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Создать пост';
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

        <?= $form->field($model, 'tagNames')->textInput(['placeholder' => 'Введите теги через запятую']) ?>

        <?= $form->field($model, 'is_public')->checkbox() ?>

        <?= $form->field($model, 'is_request_only')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>


