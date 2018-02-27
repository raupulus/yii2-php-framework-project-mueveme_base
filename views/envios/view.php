<?php

use yii\data\SqlDataProvider;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Envios */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = $this->title;

$js = <<<EOT

    $('.boton-votos:even').closest('form').on('beforeSubmit', function() {
        var formulario = $(this);
        enviar(formulario, true);

        return false;
    });

    $('.boton-votos:odd').closest('form').on('beforeSubmit', function() {
        var formulario = $(this);
        enviar(formulario, false);

        return false;
    });

    function enviar(formulario, boolPositivo) {
        var comen = formulario.closest('.com-heading');
        $.ajax({
            url: formulario.attr('action'),
            type: 'POST',
            data: {
                comentario_id: comen.data('comentario_id'),
                positivo: boolPositivo ? 1 : 0
            },
            success: function (data) {
                if (data.save) {
                    var votosPositivos = comen.find('.votos-positivos');
                    var votosNegativos = comen.find('.votos-negativos');
                    if (boolPositivo) {
                        votosPositivos.text(
                            +votosPositivos.text() + 1
                        );
                    } else {
                        votosNegativos.text(
                            +votosNegativos.text() + 1
                        );
                    }
                    if (data.update) {
                        if (boolPositivo) {
                            votosNegativos.text(
                                +votosNegativos.text() - 1
                            );
                        } else {
                            votosPositivos.text(
                                +votosPositivos.text() - 1
                            );
                        }
                    }
                    comen.find('.positivo').prop('disabled', boolPositivo);
                    comen.find('.negativo').prop('disabled', !boolPositivo);
                }
            }
        });
    }

EOT;

$this->registerJs($js);

?>
<div class="envios-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>

    <!-- <p>

        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p> -->


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'url:url',
            'entradilla',
            [
                'label'=>'Publicado por',
                'value'=> $model->usuario->nombre,
            ],
            'created_at:datetime',
            [

                'label'=>'Categoría',
                'value'=>$model->categoria->denominacion,
            ],
            [
                'attribute' => 'Imagen',
                'value' => $model->urlImagen,
                'format' => 'image',
            ],
        ],
    ]) ?>
    <hr>

    <h3>Comentarios</h3>
    <?= ListView::widget([
        'dataProvider'=>$dataProvider,
        'itemView'=>'_comentario',
        'summary'=>'',
    ]) ?>

    <span class='label label-primary'>
        <?= $numeroComentarios ?> comentarios
    </span>

    <hr>


        <?php $form = ActiveForm::begin([
            'action'=>['comentarios/create'],
            ]) ?>

            <?= $form->field($modeloComentarios, 'texto')->textarea([
                'rows'=>5,
                'placeholder'=>'Escribe un comentario ...',
            ]) ?>

            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::hiddenInput('envio_id', $modeloComentarios->envio_id) ?>
                <?= Html::hiddenInput('usuario_id', Yii::$app->user->identity->id) ?>
            <?php endif ?>

            <?= Html::submitButton('Añadir', ['class'=>'btn-xs btn-success'])?>
        <?php ActiveForm::end() ?>





</div>
