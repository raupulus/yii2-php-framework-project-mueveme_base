<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Detalles de la noticia';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['movimientos/mover-ajax']);

$js = <<<EOT
$('.btn-mover').on('click', function(e) {
    e.preventDefault();
    $.ajax({
        url: '$url',
        type: 'POST',
        data: {
            id: $(this).parent().siblings('#id-envio').val()
        },
        success: function(data) {
            $(e.target).closest('form').siblings('h4').text(data);
            $(e.target).text('¡hecho!');
            $(e.target).prop('disabled', true);
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
                <?php if(count($comentarios) > 0): ?>
                    <div class="page-header">
                        <h2><small class="pull-right"><?= count($comentarios) . ' comentarios' ?></small> Comentarios</h2>
                    </div>
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
                                        <?= $comentario->usuario->nombre ?>
                                    </h4>
                                    <?= $comentario->texto ?>
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
