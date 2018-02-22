<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Envios */

$this->title = 'Crear Noticias';
$this->params['breadcrumbs'][] = ['label' => 'Envios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="envios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
