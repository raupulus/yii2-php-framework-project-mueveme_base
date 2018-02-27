<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Comentarios;
use app\models\Envios;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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

        if ($model->load(Yii::$app->request->post())) {
            $model->foto = UploadedFile::getInstance($model, 'foto');
            if ($model->save() && $model->upload()) {
                if ($model->foto !== null) {
                    $model->foto = null;
                    $model->url_img = str_replace('dl=0', 'dl=1', $model->uploadDropbox());
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Noticia publicada correctamente.');
                    return $this->redirect(['envios/candidatas']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCandidatas($categoria_id = null)
    {
        $noticias = Envios::find();

        if (is_numeric($categoria_id) && Categorias::findOne($categoria_id)) {
            $noticias = $noticias->where(['categoria_id' => $categoria_id]);
        }

        $min = Yii::$app->params['minMovimientos'];
        $noticias = $noticias
            ->select('envios.*, COUNT(movimientos.*)')
            ->joinWith('movimientos')
            ->groupBy('envios.id')
            ->having("COUNT(movimientos.*) < $min")
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('candidatas', [
            'noticias' => $noticias,
        ]);
    }

    public function actionDetalles($id)
    {
        if (!is_numeric($id) || !Envios::findOne($id)) {
            return $this->goHome();
        }

        $model = new Comentarios();

        return $this->render('detalles', [
            'envio' => $this->findModel($id),
            'model' => $model,
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
