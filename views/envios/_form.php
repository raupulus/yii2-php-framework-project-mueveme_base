<?php

use app\models\Categorias;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Envios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="envios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entradilla')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
                Categorias::find()
                ->select('nombre')
                ->indexBy('id')
                ->column()) ?>

    <div class="form-group">
        <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
