<?php

namespace app\controllers;

use app\models\Envios;
use app\models\Movimientos;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MovimientosController extends \yii\web\Controller
{
    /**
     * Aplica un 'movimiento' a la noticia.
     * @return [type] [description]
     */
    public function actionMoverAjax()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash(
                'danger',
                'Para realizar un movimiento, debes loguearte previamente.'
            );
            return $this->redirect(['/site/login']);
        }

        if (($id = Yii::$app->request->post('id')) === null) {
            throw new NotFoundHttpException('Falta la noticia.');
        }

        if (($envio = Envios::findOne($id)) === null) {
            throw new NotFoundHttpException('La noticia no existe.');
        }

        $mov = new Movimientos([
            'usuario_id' => Yii::$app->user->identity->id,
            'envio_id' => $envio->id,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($mov->save()) {
            return count($envio->movimientos);
        }
        return 0;
    }
}
