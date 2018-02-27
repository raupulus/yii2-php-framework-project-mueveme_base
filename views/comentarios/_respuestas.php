<?php

use yii\helpers\Html;
use Yii;
?>


<div class='panel panel-info'>
    <div class='panel-heading'>
        <?= Html::encode($model->usuario->nombre)?>
        <?= Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </div>
    <div class='panel-body'>
        <?= Html::encode($model->texto) ?>
    </div>

</div>
