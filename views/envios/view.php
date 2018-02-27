<?php

use app\models\Movimientos;

use yii\helpers\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <?= Html::a(Html::encode($envio->titulo), $envio->url, [
                'target' => '_blank'
                ]) ?>

                <div class="col-md-2 text-center">
                    <span class="badge text-center"><?= $envio->categoria->nombre ?></span>
                </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-md-2 text-center">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    Movimientos
                </div>
                <div class="panel-body">
                    <h4 style="margin-top: 0" id="movimientos">
                        <?= count($envio->movimientos) ?>
                    </h4>
                    <?php
                    // Solo comprobamos si ha sido movido, si está logueado
                    $yaMovido = false;
                    if (!Yii::$app->user->isGuest) {
                        $yaMovido = Movimientos::findOne([
                            'usuario_id' => Yii::$app->user->identity->id,
                            'envio_id' => $envio->id
                        ]) !== null;
                    }
                    ?>
                    <?php if ($yaMovido): ?>
                        <?= Html::a('¡hecho!', null, [
                            'disabled' => 'disabled',
                            'class' => 'btn btn-xs btn-warning',
                            ]) ?>
                    <?php else: ?>
                        <?= Html::beginForm(
                            ['movimientos/mover-ajax'],
                            'post'
                            ) ?>
                        <?= Html::hiddenInput('id',
                            $envio->id,
                            ['id' => 'id-envio']
                            ) ?>
                        <div class="form-group">
                            <?= Html::submitButton(
                                'Muéveme',
                                [
                                    'class' => 'btn btn-xs btn-warning btn-mover',
                                ]
                            ) ?>
                        </div>
                        <?= Html::endForm() ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="row" style="height:70px">
                <small>
                    Creado por
                    <strong><?= Html::encode($envio->usuario->nombre) ?></strong>
                    <?= Yii::$app
                    ->formatter
                    ->asRelativeTime($envio->created_at) ?>
                </small><br>
                <?= Html::encode($envio->entradilla) ?>
            </div>
            <!-- <hr> -->
            <div class="row" >
                <hr>
                <span class="badge badge-warning text-center">
                    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                    <?= Html::a(
                        count($envio->comentarios). ' comentarios',
                        [
                            'envios/detalles',
                            'id' => $envio->id,
                        ],
                        [
                            'style' => ['color' => 'white'],
                        ]
                        ) ?>
                    </span>

            </div>
        </div>
        <div class="col-md-3">
            <?php if ($envio->url_img !== null): ?>
                <?= Html::img(
                    $envio->url_img,
                    ['class' => 'img-thumbnail']
                ) ?>
            <?php endif ?>
        </div>
        <div class="col-md-10">

        </div>
    </div>
</div>
