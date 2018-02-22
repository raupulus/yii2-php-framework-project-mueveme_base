<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;

if (Yii::$app->user->isGuest) {
    $tieneVotoPositivo = false;
    $tieneVotoNegativo = false;
} else {
    $tieneVotoPositivo = $model->getVotos()->where(
        [
            'usuario_id' => Yii::$app->user->identity->id,
            'positivo' => true,
        ])
        ->exists();
    $tieneVotoNegativo = $model->getVotos()->where(
        [
            'usuario_id' => Yii::$app->user->identity->id,
            'positivo' => false,
        ])
        ->exists();
}

?>

<div class='panel panel-primary'>
    <div class='panel-heading com-heading' data-comentario_id="<?= $model->id ?>">
        <?= Html::encode($model->usuario->nombre) ?> <?= Yii::$app->formatter->asRelativeTime($model->created_at) ?>
        <?php $form = ActiveForm::begin([
            'id' => "votar-positivo$index",
            'action' => ['votos/votar'],
            'options' => [
                'style' => 'display:inline',
                'class' => 'form-positivo',
            ],
            ]) ?>

            <?= Html::submitButton(
                Html::tag(
                    'span',
                    '',
                    [
                        'class' => 'glyphicon glyphicon-thumbs-up',
                        'aria-hidden' => 'true',
                    ]
                ),
                [
                    'class' => 'btn boton-votos btn-primary positivo',
                    'disabled' => $tieneVotoPositivo,
                    'aria-label' => 'Left Align',
                    'title' => 'Voto positivo',
                ]
                )?>
                <span class="votos-positivos">
                    <?= count($model->getVotos()->where(['positivo' => true])->all()) ?>
                </span>
        <?php ActiveForm::end() ?>

        <?php $form = ActiveForm::begin([
            'id' => "votar-negativo$index",
            'action' => ['votos/votar'],
            'options' => [
                'style' => 'display:inline',
                'class' => 'form-negativo',
            ],
            ]) ?>
            <?= Html::submitButton(
                Html::tag(
                    'span',
                    '',
                    [
                        'class' => 'glyphicon glyphicon-thumbs-down',
                        'aria-hidden' => 'true',
                    ]
                ),
                [
                    'class' => 'btn boton-votos btn-primary negativo',
                    'disabled' => $tieneVotoNegativo,
                    'aria-label' => 'Left Align',
                    'title' => 'Voto negativo',
                ]
                )?>
                <span class="votos-negativos">
                    <?= count($model->getVotos()->where(['positivo' => false])->all()) ?>
                </span>
        <?php ActiveForm::end() ?>

    </div>
    <div class='panel-body'>
        <?= Html::encode($model->texto) ?>
    </div>
</div>