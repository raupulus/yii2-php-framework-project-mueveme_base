<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Envios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="envios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entradilla')->textarea(['maxlength' => true, 'rows'=>5]) ?>


    <?= $form->field($model, 'categoria_id')->dropdownList([
        'Categorias'=>$items,
    ]); ?>
    <?= $form->field($model, 'imagen')->fileInput() ?>

    <!-- <?= $form->field($model, 'usuario_id')->textInput() ?> -->



    <div class="form-group">
        <?= Html::submitButton('Crear', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
