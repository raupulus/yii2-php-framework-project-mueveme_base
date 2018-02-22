<?php

namespace app\controllers;

use Yii;
use app\models\Movimientos;
use app\models\MovimientosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MovimientosController implements the CRUD actions for Movimientos model.
 */
class MovimientosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Movimientos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MovimientosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Movimientos model.
     * @param integer $usuario_id
     * @param integer $envio_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($usuario_id, $envio_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($usuario_id, $envio_id),
        ]);
    }

    /**
     * Creates a new Movimientos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Movimientos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'usuario_id' => $model->usuario_id, 'envio_id' => $model->envio_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Movimientos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $usuario_id
     * @param integer $envio_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($usuario_id, $envio_id)
    {
        $model = $this->findModel($usuario_id, $envio_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'usuario_id' => $model->usuario_id, 'envio_id' => $model->envio_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Movimientos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $usuario_id
     * @param integer $envio_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($usuario_id, $envio_id)
    {
        $this->findModel($usuario_id, $envio_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Movimientos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $usuario_id
     * @param integer $envio_id
     * @return Movimientos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($usuario_id, $envio_id)
    {
        if (($model = Movimientos::findOne(['usuario_id' => $usuario_id, 'envio_id' => $envio_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
