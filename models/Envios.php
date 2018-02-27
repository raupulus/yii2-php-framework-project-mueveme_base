<?php

namespace app\models;

use Spatie\Dropbox\Exceptions\BadRequest;
use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "envios".
 *
 * @property int $id
 * @property string $url
 * @property string $titulo
 * @property string $entradilla
 * @property int $usuario_id
 * @property int $categoria_id
 * @property string $url_img
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comentarios[] $comentarios
 * @property Categorias $categoria
 * @property Usuarios $usuario
 * @property Movimientos[] $movimientos
 */
class Envios extends \yii\db\ActiveRecord
{
    /**
     * Contiene la foto de la noticia.
     * @var UploadedFile
     */
    public $foto;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'envios';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'foto', 'url_img',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'titulo', 'entradilla'], 'required'],
            [['usuario_id'], 'default', 'value' => null],
            [['url'], 'url', 'defaultScheme' => 'http'],
            [['categoria_id'], 'integer'],
            [['created_at', 'updated_at', 'url_img'], 'safe'],
            [['url', 'titulo', 'entradilla'], 'string', 'max' => 255],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::className(), 'targetAttribute' => ['categoria_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['foto'], 'file', 'extensions' => 'jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'URL de la fuente',
            'titulo' => 'Título de la noticia',
            'entradilla' => 'Resumen de la noticia',
            'usuario_id' => 'Usuario ID',
            'categoria_id' => 'Categoría',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function upload()
    {
        if ($this->foto === null) {
            return true;
        }
        $nombre = Yii::getAlias('@uploads/') . $this->id . '.jpg';
        $res = $this->foto->saveAs($nombre);
        if ($res) {
            Image::thumbnail($nombre, 200, null)->save($nombre);
        }
        return $res;
    }

    public function uploadDropbox()
    {
        $id = $this->id;
        $fichero = "$id.jpg";
        $client = new \Spatie\Dropbox\Client(getenv('DROPBOX_TOKEN'));
        try {
            $client->delete($fichero);
        } catch (BadRequest $e) {
            // No se hace nada
        }

        $client->upload(
            $fichero,
            file_get_contents(Yii::getAlias("@uploads/$fichero")),
            'overwrite'
        );
        $res = $client->createSharedLinkWithSettings($fichero, [
            'requested_visibility' => 'public',
        ]);
        return $res['url'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['envio_id' => 'id'])->inverseOf('envio');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('envios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categorias::className(), ['id' => 'categoria_id'])->inverseOf('envios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovimientos()
    {
        return $this->hasMany(Movimientos::className(), ['envio_id' => 'id'])->inverseOf('envio');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->usuario_id = Yii::$app->user->identity->id;
            } else {
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }
}
