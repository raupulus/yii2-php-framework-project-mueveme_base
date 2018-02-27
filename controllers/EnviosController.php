<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Comentarios;
use app\models\Envios;
use app\models\EnviosSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\widgets\ListView;

/**
 * EnviosController implements the CRUD actions for Envios model.
 */
class EnviosController extends Controller
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
            'acces' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Envios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EnviosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Busca las noticias que tenga una determinada categoria.
     * @param  int $categoria_id Identificador de la categoria.
     * @return [type]               [description]
     */
    public function actionBuscar($categoria_id = null)
    {
        if (Yii::$app->request->isAjax) {
            $dataProvider = new ActiveDataProvider([
                'query' => Envios::find()
                    ->where(['categoria_id' => $categoria_id]),
                    'pagination' => false,
                    'sort' => false,
            ]);

            Yii::$app->assetManager->bundles = [
                'yii\web\JqueryAsset' => false,
                'yii\web\YiiAsset' => false,
            ];

            return $this->renderAjax('index', [
                'models' => $dataProvider,
            ]);

            // return ListView::widget([
            //     'dataProvider' => $dataProvider,
            //     'itemView' => '@app/views/envios/_envio',
            //     'summary' => '',
            // ]);
        }
        $categorias = Categorias::find()->all();

        return $this->render('buscar', [
            'categorias' => $categorias,
        ]);
    }

    /**
     * Displays a single Envios model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!ctype_digit($id)) {
            throw new NotFoundHttpException('Paràmetro incorrecto');
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Comentarios::find()->where(['envio_id' => $id]),
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $modeloComentarios = new Comentarios([
            'envio_id' => $id,
        ]);

        $numeroComentarios = $dataProvider->query->count();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
            'numeroComentarios' => $numeroComentarios,
            'modeloComentarios' => $modeloComentarios,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionUltimas()
    {
        $models = new ActiveDataProvider([
            'query' => Envios::find(),
            'pagination' => [
                'pageSize' => 4,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('index', [
            'models' => $models,
        ]);
    }

    /**
     * Creates a new Envios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Envios([
            'usuario_id' => Yii::$app->user->identity->id,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $model->imagen = UploadedFile::getInstance($model, 'imagen');
            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $items = Categorias::find()
            ->select(['denominacion'])
            ->indexBy('id')
            ->column();

        return $this->render('create', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    /**
     * Updates an existing Envios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Envios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
