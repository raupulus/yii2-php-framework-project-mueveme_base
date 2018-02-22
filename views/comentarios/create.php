<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Comentarios */

$this->title = 'Añadir comentario';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comentarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
