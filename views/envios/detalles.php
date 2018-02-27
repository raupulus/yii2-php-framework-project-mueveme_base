<?php

/* @var $this yii\web\View */
use app\models\Votos;

use yii\web\View;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

$this->title = 'Detalles de la noticia';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<EOT
function estiloVoto(e, data) {
    var clase1 = (data) ? 'text-danger' : 'text-success';
    var clase2 = (data) ? 'text-success' : 'text-danger';

    $(e.target).closest('form').parent().siblings('div').find('span').first().removeClass(clase1);
    $(e.target).closest('form').parent().siblings('div').find('span').first().addClass('text-grey');
    $(e.target).closest('form').parent().siblings('div').find('button').prop('disabled', false);
    $(e.target).removeClass('text-grey text-success text-danger');
    $(e.target).addClass(clase2);
    $(e.target).parent().prop('disabled', true);
}
EOT;
$this->registerJs($js, View::POS_HEAD);

$urlVotar = Url::to(['votos/votar-ajax']);
$urlVotos = Url::to(['votos/votos-ajax']);
$js = <<<EOT
$('.btn-votar').on('click', function(e) {
    e.preventDefault();
    $.ajax({
        url: '$urlVotar',
        type: 'POST',
        data: {
            id: $(this).parent().siblings('#id-comentario').val(),
            valor: $(this).parent().siblings('#id-valor').val(),
        },
        success: function(data) {
            $.getJSON(
                '$urlVotos',
                { id: $(e.target).parent().parent().siblings('#id-comentario').val() },
                function(data) {
                    var idPulsado = $(e.target).parent().attr('id');
                    if (idPulsado == 'btn-pos') {
                        $(e.target).parent().siblings('#votos-pos').text(data[1]);
                        $(e.target).closest('form').parent().siblings('div').find('#votos-neg').text(data[0]);
                    } else {
                        $(e.target).parent().siblings('#votos-neg').text(data[0]);
                        $(e.target).closest('form').parent().siblings('div').find('#votos-pos').text(data[1]);
                    }
                }
            );
            estiloVoto(e, data);
        }
    })
})
EOT;
$this->registerJs($js);
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h2>Detalles de la noticia</h2><hr>
                <div class="panel-group">
                    <?= $this->render('/envios/view',[
                        'envio' => $envio
                    ]) ?>
                </div>
                <?php $comentarios = $envio->getComentarios()
                    ->orderBy(['created_at' => SORT_DESC])
                    ->all();
                ?>
                <div class="page-header">
                    <h2>
                        <small class="pull-right">
                            <?= count($comentarios) . ' comentarios' ?>
                        </small>
                        Comentarios
                    </h2>
                </div>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?php $form = ActiveForm::begin([
                        'action' => ['comentarios/create'],
                        'id' => 'id-comentar',
                        'method' => 'post'
                    ]); ?>

                    <?= $form->field($model, 'texto')
                        ->textarea(['rows' => 3])
                        ->label(false) ?>

                    <?= $form->field($model, 'envio_id')
                        ->hiddenInput(['value' => $envio->id])
                        ->label(false) ?>

                    <div class="form-group text-right">
                        <?= Html::submitButton(
                            'Comentar',
                            ['class' => 'btn btn-warning']
                        ) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php endif ?>
                <?php if(count($comentarios) > 0): ?>
                    <div class="comments-list">
                        <?php foreach ($comentarios as $comentario): ?>
                            <div class="media">
                                <p class="pull-right">
                                    <small>
                                        <?= Yii::$app
                                        ->formatter
                                        ->asRelativeTime($comentario->created_at) ?>
                                    </small>
                                </p>
                                <a class="media-left" href="#">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading user_name">
                                        <?= Html::encode($comentario->usuario->nombre) ?>
                                    </h4>
                                    <?= Html::encode($comentario->texto) ?>
                                    <br>
                                    <?php
                                    $yaVotado = false;
                                    if (!Yii::$app->user->isGuest) {
                                        $voto = Votos::findOne([
                                            'usuario_id' => Yii::$app->user->identity->id,
                                            'comentario_id' => $comentario->id
                                        ]);
                                        $yaVotado = ($voto !== null);
                                    }
                                    ?>
                                    <div style="display: flex">
                                        <?php $resPos = $resNeg = 'text-grey'; ?>
                                        <?php if ($yaVotado): ?>
                                            <?php if ($voto->voto): ?>
                                                <?php $resPos = 'text-success'; ?>
                                            <?php else: ?>
                                                <?php $resNeg = 'text-danger'; ?>
                                            <?php endif ?>
                                        <?php endif ?>
                                        <div style="margin-right: 10px">
                                            <?= Html::beginForm(
                                                ['votos/votar-ajax'],
                                                'post'
                                                ) ?>
                                            <?= Html::hiddenInput('id',
                                                $comentario->id,
                                                ['id' => 'id-comentario']
                                                ) ?>
                                            <?= Html::hiddenInput('valor',
                                                1,
                                                ['id' => 'id-valor']
                                                ) ?>
                                            <div class="form-group">
                                                <?php $class = ['id' => 'btn-pos', 'class' => 'btn btn-lg btn-link btn-votar'] ?>
                                                <?php if ($yaVotado && $voto->voto): ?>
                                                    <?php $class = array_merge($class, ['disabled' => 'disabled']) ?>
                                                <?php endif ?>
                                                <?= Html::submitButton(
                                                    '<span class="glyphicon glyphicon-thumbs-up '. $resPos .'"></span>',
                                                    $class
                                                ) ?>
                                                <span id="votos-pos" class="text-success" style="font-weight: bold">
                                                    <?= count($comentario->getVotos()->where(['voto' => true])->all()) ?>
                                                </span>
                                            </div>
                                            <?= Html::endForm() ?>
                                        </div>
                                        <div>
                                            <?= Html::beginForm(
                                                ['votos/votar-ajax'],
                                                'post'
                                                ) ?>
                                            <?= Html::hiddenInput('id',
                                                $comentario->id,
                                                ['id' => 'id-comentario']
                                                ) ?>
                                            <?= Html::hiddenInput('valor',
                                                0,
                                                ['id' => 'id-valor']
                                                ) ?>
                                            <div class="form-group">
                                                <?php $class = ['id' => 'btn-neg', 'class' => 'btn btn-lg btn-link btn-votar'] ?>
                                                <?php if ($yaVotado && !$voto->voto): ?>
                                                    <?php $class = array_merge($class, ['disabled' => 'disabled']) ?>
                                                <?php endif ?>
                                                <?= Html::submitButton(
                                                    '<span class="glyphicon glyphicon-thumbs-down '. $resNeg .'"></span>',
                                                    $class
                                                ) ?>
                                                <span id="votos-neg" class="text-danger" style="font-weight: bold">
                                                    <?= count($comentario->getVotos()->where(['voto' => false])->all()) ?>
                                                </span>
                                            </div>
                                            <?= Html::endForm() ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <h4>No hay comentarios disponibles</h4>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
