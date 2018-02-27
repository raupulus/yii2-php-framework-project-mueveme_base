<?php
use yii\helpers\Url;
use yii\helpers\Html;


$this->title = 'Buscar una noticia';
$this->params['breadcrumbs'][] = $this->title;

$urlEnviosBuscar = Url::to(['envios/buscar']);
$js = <<<EOT
    $('#boton-buscar').on('click', function(evento) {
        evento.preventDefault();

        $.ajax({
            url: '$urlEnviosBuscar',
            type: 'GET',
            data: {categoria_id: $('select').val()},
            success: function (data) {
                $('#respuesta').html(data);
            }
        })
    })
EOT;

$this->registerJs($js);
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <h4><?= $this->title ?></h4>
    </div>
    <div class="panel-body">
        <?= Html::beginForm(['envios/buscar'],'get',['class'=>'form-inline']) ?>
        <div class="form-group">
            <label>Categoría</label>
            <select class="form-control">
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria->id ?>"><?= $categoria->denominacion ?></option>
                <?php endforeach; ?>
            </select>
        </div>

            <button id="boton-buscar" type="submit" class='btn btn-success'>
                <span class='glyphicon glyphicon-zoom-in' aria-hidden="true"></span>
            </button>
        <?= Html::endForm()?>
    </div>

</div>

<hr>
<div id='respuesta'>

</div>
