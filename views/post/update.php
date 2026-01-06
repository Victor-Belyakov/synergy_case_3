<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Редактировать пост';
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="post-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

        <?= $form->field($model, 'tagNames')->textInput(['placeholder' => 'Введите теги через запятую']) ?>

        <?= $form->field($model, 'is_public')->checkbox() ?>

        <?= $form->field($model, 'is_request_only')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>


