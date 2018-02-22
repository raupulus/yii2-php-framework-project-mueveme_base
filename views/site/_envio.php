<?php

/* @var $this yii\web\View */
/* @var $models app\models\Envios */
use yii\helpers\Html;

use yii\widgets\ActiveForm;


$this->title = Yii::$app->name;

if (Yii::$app->user->isGuest) {
    $tieneMovimiento = false;
} else {
    $tieneMovimiento = $model->getMovimientos()
    ->where(['usuario_id' => Yii::$app->user->identity->id])->exists();
}

$js = <<<EOT
$('#mueveme-ajax$index').on('beforeSubmit', function() {
    mover($index, $model->id);
    return false;
});
EOT;
$this->registerJs($js);

?>
<div class="site-index">

    <div class="body-content">
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-warning mov-panel">
                    <div class="panel-heading mov-heading">
                        <h5 class="panel-title text-center">
                            <span id="mov<?= $index ?>"><?= count($model->movimientos) ?></span><br>
                            <span class="mov-texto">movimientos</span>
                        </h5>
                    </div>
                    <div class="panel-body text-center">
                        <?php $form = ActiveForm::begin([
                            'id' => "mueveme-ajax$index",
                            'action' => ['movimientos/mover'],
                            ]) ?>
                            <div>
                                <?php if ($tieneMovimiento): ?>
                                    <?= Html::tag(
                                        'span',
                                        '¡hecho!',
                                        ['class' => 'label etiquieta label-warning']
                                    ) ?>
                                <?php else: ?>
                                <?= Html::submitButton(
                                    'Muéveme',
                                    [
                                        'class' => 'btn btn-xs btn-warning',
                                        'id' => "btn-mover$index"
                                    ]
                                    )?>
                                <?php endif ?>
                            </div>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <h4><?= Html::a(Html::encode($model->titulo), $model->url) ?></h4>
                <h6>
                    Por <b><?= Html::encode($model->usuario->nombre) ?></b>
                    &nbsp;&nbsp;<?= Yii::$app->formatter
                    ->asDateTime($model->created_at)
                    ?>
                    , publicado: <?= Yii::$app->formatter
                        ->asRelativeTime($model->created_at) ?>
                </h6>
                <p><?= Html::encode($model->entradilla) ?></p>

                <?= Html::a('Ver mas información', ['envios/view', 'id'=>$model->id], ['class'=>'btn-xs btn-primary'])?>
                <span class='label label-primary'>
                    <?= $model->getComentarios()->count() ?> comentarios
                </span>

                <hr>

            </div>
        </div>
    </div>
</div>
