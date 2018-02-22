<?php

/* @var $this yii\web\View */

use app\models\Categorias;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

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
                <div class="row">
                    <div class="col-md-8">
                        <h2>Noticias</h2>
                    </div>
                    <div class="col-md-4">
                        <?= Html::beginForm(['index'], 'get', [
                            'class' => 'form-inline',
                            'style' => 'margin-top:20px'
                            ]) ?>
                            <div class="form-group">
                                <label>Categoría: </label>
                                <?php ?>
                                <?= Html::dropDownList('categoria_id',
                                Yii::$app->request->get('categoria_id'),
                                ArrayHelper::map(
                                    Categorias::find()->all(),
                                    'id',
                                    'nombre'
                                )
                                ,
                                [
                                    'prompt' => '-- Selecciona --',
                                    'class' => 'form-control',
                                ]) ?>
                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                            </div>
                            <?= Html::endForm() ?>

                    </div>

                </div>

                <hr>
                <?php if(count($noticias) > 0): ?>
                    <div class="panel-group">
                    <?php foreach ($noticias as $envio): ?>
                        <?= $this->render('/envios/view',[
                            'envio' => $envio
                        ]) ?>
                    <?php endforeach ?>
                </div>
                <?php else: ?>
                    <h4>No hay noticias disponibles</h4>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
