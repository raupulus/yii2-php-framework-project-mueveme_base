<?php

namespace app\controllers;

use app\models\Votos;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class VotosController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['votar'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['votar'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionVotar()
    {
        $usuario_id = Yii::$app->user->identity->id;
        $comentario_id = Yii::$app->request->post('comentario_id');
        $positivo = Yii::$app->request->post('positivo');
        $voto = Votos::find()->where([
            'usuario_id' => $usuario_id,
            'comentario_id' => $comentario_id,
        ])->one();
        if ($voto !== null) {
            if ($voto->positivo == $positivo) {
                throw new BadRequestHttpException();
            }
            $voto->positivo = $positivo;
            $update = true;
        } else {
            $voto = new Votos([
                'usuario_id' => $usuario_id,
                'comentario_id' => $comentario_id,
                'positivo' => $positivo,
            ]);
            $update = false;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'save' => $voto->save(),
            'update' => $update,
            ];
    }
}
