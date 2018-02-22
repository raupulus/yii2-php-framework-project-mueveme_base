<?php

namespace app\models;

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
            ['url', 'url'],
            [['usuario_id'], 'default', 'value' => null],
            [['usuario_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url', 'titulo'], 'string', 'max' => 255],
            [['entradilla'], 'string', 'max' => 1000],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
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
