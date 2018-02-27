<?php

namespace app\controllers;

use app\models\Comentarios;
use app\models\Votos;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class VotosController extends \yii\web\Controller
{
    /**
     * Aplica un voto a un comentario.
     * @return bool Devuelve true si el voto es positivo o false si es negativo
     */
    public function actionVotarAjax()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash(
                'danger',
                'Para realizar un voto, debes loguearte previamente.'
            );
            return $this->redirect(['/site/login']);
        }

        if (($valor = Yii::$app->request->post('valor')) === null) {
            throw new NotFoundHttpException('Falta el valor.');
        }

        if (($id = Yii::$app->request->post('id')) === null) {
            throw new NotFoundHttpException('Falta el comentario.');
        }

        if (($comentario = Comentarios::findOne($id)) === null) {
            throw new NotFoundHttpException('El comentario no existe.');
        }

        $voto = Votos::findOne([
            'usuario_id' => Yii::$app->user->identity->id,
            'comentario_id' => $comentario->id,
        ]);

        if ($voto !== null) {
            $voto->voto = ($voto->voto) ? false : true;
            $voto->save();
            return $voto->voto;
        }

        $voto = new Votos([
            'usuario_id' => Yii::$app->user->identity->id,
            'comentario_id' => $comentario->id,
            'voto' => $valor,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($voto->save()) {
            return $voto->voto;
        }
        return false;
    }

    /**
     * Devuelve el total de votos positivos y negativos.
     * @param  int   $id El id del comentario
     * @return array     El total de votos positivos y negativos
     */
    public function actionVotosAjax($id)
    {
        if (($comentario = Comentarios::findOne($id)) === null) {
            throw new NotFoundHttpException('El comentario no existe.');
        }

        $res = [
            count($comentario->getVotos()->where(['voto' => false])->all()),
            count($comentario->getVotos()->where(['voto' => true])->all()),
        ];

        return json_encode($res);
    }
}
