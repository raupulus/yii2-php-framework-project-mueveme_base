<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/mov.png']); ?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/mov.png', [
            'alt' => 'Muéveme',
            'width' => '25px;',
            'style' => 'display: inline; margin-top: -3px;',
        ]) . ' ' . Yii::$app->name,
        // 'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $items = [
        ['label' => 'Portada', 'url' => ['/site/index']],
        ['label' =>'Crear una noticia', 'url'=>['/envios/create']],
        ['label' =>'Últimas noticias', 'url'=>['/envios/ultimas']],
        ['label'=>'Buscar noticia', 'url'=>['envios/buscar']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];

    if (Yii::$app->user->isGuest) {
        $items[] = [
            'label' => 'Usuarios',
            'items' => [
                [
                    'label' => 'Login',
                    'url' => ['/site/login']
                ],
                [
                    'label' => 'Registrarse',
                    'url' => ['usuarios/create'],
                ]
            ],
        ];
    } else {
        $items[] = [
            'label' => 'Usuarios (' . Yii::$app->user->identity->nombre . ')',
            'items' => [
                [
                    'label'=>'Modificar datos',
                    'url'=>['usuarios/update', 'id'=>Yii::$app->user->identity->id],
                ],
                [
                    'label' => 'Logout',
                    'url' => ['site/logout'],
                    'linkOptions' => ['data-method' => 'POST'],
                ],

            ],
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container total">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Yii::$app->name . ' ' . date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
