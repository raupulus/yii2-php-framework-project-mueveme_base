<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
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
                'only' => ['update'],
                'rules' => [
                    [
                        'actions' => ['update'],
                        'allow' => 'update',
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->id == Yii::$app->request->get('id');
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UsuariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);
    }

    /**
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuarios(['scenario' => Usuarios::ESCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($model->email)
                ->setSubject('Activación de cuenta')
                ->setHtmlBody(Html::a('Activación de cuenta', Url::to(['usuarios/activar', 'token' => $model->token_val], true)))
                ->send();
            Yii::$app->session->setFlash('info', 'Se ha enviado un correo a la dirección indicada');

            return $this->redirect(['site/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionActivar($token = null)
    {
        $usuario = Usuarios::findOne(['token_val' => $token]);

        if ($usuario !== null) {
            $usuario->token_val = null;
            $usuario->save();

            Yii::$app->session->setFlash('success', 'La cuenta se ha activado correctamente');
            return $this->redirect(['site/login', 'nombre' => $usuario->nombre]);
        }

        Yii::$app->session->setFlash('danger', 'La cuenta ya ha sido activada');
        return $this->redirect(['site/login']);
    }

    /**
     * Updates an existing Usuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!ctype_digit($id)) {
            throw new NotFoundHttpException('Parámetro incorrecto');
        }

        $model = $this->findModel($id);

        $model->scenario = Usuarios::ESCENARIO_UPDATE;
        $model->password = '';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página requerida no existe.');
    }
}
