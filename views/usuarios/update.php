<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = 'Actualizar mi perfil';
$this->params['breadcrumbs'][] = ['label' => 'Mi Perfil', 'url' => ['mi-perfil']];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="usuarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
