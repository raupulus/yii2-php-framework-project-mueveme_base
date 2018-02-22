<?php

namespace app\controllers;

use app\models\Comentarios;
use app\models\Envios;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * EnviosController implements the CRUD actions for Envios model.
 */
class EnviosController extends Controller
{
    /**
     * Creates a new Envios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash(
                'error',
                'Debes loguearte previamente para poder publicar una noticia.'
            );
            return $this->redirect(['/site/login']);
        }

        $model = new Envios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Noticia publicada correctamente.');
            return $this->goHome();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDetalles($id)
    {
        if (!is_numeric($id) || !Envios::findOne($id)) {
            return $this->goHome();
        }

        $comentarios = Comentarios::find()
            ->where(['envio_id' => $id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('detalles', [
            'envio' => $this->findModel($id),
            'comentarios' => $comentarios,
        ]);
    }

    /**
     * Finds the Envios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Envios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Envios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
