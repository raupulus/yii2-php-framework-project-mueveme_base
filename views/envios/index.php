<?php

/* @var $this yii\web\View */
/* @var $models app\models\Envios */
use yii\helpers\Html;

use yii\widgets\ListView;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->name;

$js = <<<EOT
    function mover(index, model_id) {
        $.ajax({
            url: $('#mueveme-ajax' + index).attr('action'),
            type: 'POST',
            data: {
                envio_id: model_id
            },
            success: function (data) {
                if (data) {
                    +$('#mov' + index).text(+$('#mov' + index).text() + 1);
                    $('#btn-mover' + index).parent()
                    .append($('<span class="label etiqueta label-warning">¡hecho!</span>'));
                    $('#btn-mover' + index).remove();
                }
                console.log(data);
            }
        });
    }
EOT;
$this->registerJs($js);

?>
<div class="site-index">



    <div class="body-content">
        <?= ListView::widget(
            [
                'dataProvider' => $models,
                'itemView' => '_envio',
                'summary' => '',
            ]
        ); ?>
    </div>
</div>
