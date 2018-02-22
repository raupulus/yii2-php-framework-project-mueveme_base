<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EnviosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Envios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="envios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Envios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Número de envios',
                'value' => function($model, $key, $index, $column){
                    return $model->getNumEnvios();
                }
            ],
            //'id',
            'url:url',
            'titulo',
            'entradilla',
            'usuario.nombre',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
