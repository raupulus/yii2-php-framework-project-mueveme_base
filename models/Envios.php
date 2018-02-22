<?php

namespace app\models;

use yii\imagine\Image;

/**
 * This is the model class for table "envios".
 *
 * @property int $id
 * @property string $url
 * @property string $titulo
 * @property string $entradilla
 * @property bool $soft_delete
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
     * La imagen para el envio.
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'titulo', 'entradilla', 'usuario_id'], 'required'],
            [['url'], 'url'],
            [['usuario_id'], 'default', 'value' => null],
            [['usuario_id'], 'integer'],
            [['url', 'titulo', 'entradilla'], 'string', 'max' => 255],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['foto'], 'file', 'extensions' => 'png'],
        ];
    }

    public function upload()
    {
        if ($this->foto === null) {
            return true;
        }
        $nombre = \Yii::getAlias('@uploads/') . $this->id . '.' . $this->foto->extension;
        $res = $this->foto->saveAs($nombre);
        if ($res) {
            Image::thumbnail($nombre, 500, null)->save($nombre);
        }
        return $res;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'titulo' => 'Titulo',
            'entradilla' => 'Entradilla',
            'soft_delete' => 'Soft Delete',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
}
