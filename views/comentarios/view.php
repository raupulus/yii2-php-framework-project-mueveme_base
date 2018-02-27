<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comentarios */

$this->title = 'Respuestas';
$this->params['breadcrumbs'][] = [
    'label' => $model->envio->titulo,
    'url' => ['envios/view', 'id'=>$model->envio->id]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comentarios-view">

    <div class='panel panel-primary'>
        <div class='panel-heading'>
            <?= Html::encode($model->usuario->nombre)?>
            <?= Yii::$app->formatter->asRelativeTime($model->created_at)?>
        </div>
        <div class='panel-body'>
            <?= Html::encode($model->texto)?>
        </div>
    </div>
    <span class='label label-primary'>
        <?= Html::encode($dataProvider->query->count())?>
        <?= $dataProvider->query->count() > 1 ? "respuestas" : "respuesta" ?>
    </span>

    <hr>

    <h3>Respuestas</h3>

    <?= ListView::widget([
        'dataProvider'=>$dataProvider,
        'itemView'=>'_respuestas',
        'summary'=>'',
    ]) ?>

    <hr>

    <?php if (!Yii::$app->user->isGuest): ?>
        <?php if ($model->usuario->id !== Yii::$app->user->identity->id): ?>
            <?php $form = ActiveForm::begin([
                'action'=>['respuestas/create'],
            ])
            ?>
                <?= $form->field($respuesta, 'texto')->textarea([
                    'rows'=>5,
                    'placeholder'=>'Escribe una respuesta ...',
                ])?>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::hiddenInput('usuario_id', Yii::$app->user->identity->id)?>
                    <?= Html::hiddenInput('comentario_id', $respuesta->comentario_id)?>
                <?php endif; ?>
                <?= Html::submitButton('Añadir', ['class'=>'btn btn-success'])?>

            <?php ActiveForm::end() ?>
        <?php endif; ?>
    <?php endif; ?>

</div>
