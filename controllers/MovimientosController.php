<?php

namespace app\controllers;

use app\models\Movimientos;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class MovimientosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['mover'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['mover'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionMover()
    {
        $usuario_id = Yii::$app->user->identity->id;
        $envio_id = Yii::$app->request->post('envio_id');
        // var_dump($_POST);
        // die();
        $movimiento = new Movimientos([
            'usuario_id' => $usuario_id,
            'envio_id' => $envio_id,
        ]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $movimiento->save();
    }
}
