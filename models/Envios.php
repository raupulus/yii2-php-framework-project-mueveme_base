<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
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
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comentarios[] $comentarios
 * @property Usuarios $usuario
 * @property Movimientos[] $movimientos
 */
class Envios extends \yii\db\ActiveRecord
{
    /**
     * Número mínimo de movimientos para aparecer en la portada.
     * @var int
     */
    const MOV_PORTADA = 3;
    /**
     * Contiene la imagen de la noticia subida en el formulario.
     * @var UploadedFile
     */
    public $imagen;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'envios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'titulo', 'entradilla', 'usuario_id', 'categoria_id'], 'required'],
            ['url', 'url'],
            [['usuario_id'], 'default', 'value' => null],
            [['usuario_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url', 'titulo'], 'string', 'max' => 255],
            [['entradilla'], 'string', 'max' => 1000],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['imagen'], 'file', 'extensions' => 'jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Dirección de fuente',
            'titulo' => 'Titulo',
            'entradilla' => 'Entrada',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Fecha de publicación',
            'updated_at' => 'Updated At',
            'categoria_id' => 'Categoría',
        ];
    }

    public function upload()
    {
        if ($this->imagen === null) {
            return true;
        }
        $nombre = Yii::getAlias('@uploads/') . $this->id . '.jpg';
        $res = $this->imagen->saveAs($nombre);
        if ($res) {
            Image::thumbnail($nombre, null, 120)->save($nombre);
        }
        return $res;
    }

    public function getUrlImagen()
    {
        return Url::to('/uploads/' . $this->id . '.jpg');
    }

    public static function getPortada()
    {
        $subQuery = self::find()
            ->select('id')
            ->joinWith('movimientos', true, 'RIGHT JOIN')
            ->groupBy(['envio_id', 'envios.id'])
            ->having('count(envio_id) >= ' . self::MOV_PORTADA);
        $query = self::find()->where(['in', 'id', $subQuery]);
        return $query;
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
    public function getMovimientos()
    {
        return $this->hasMany(Movimientos::className(), ['envio_id' => 'id'])->inverseOf('envio');
    }

    public function getCategoria()
    {
        return $this->hasOne(Categorias::className(), ['id' => 'categoria_id'])->inverseOf('envios');
    }
}
