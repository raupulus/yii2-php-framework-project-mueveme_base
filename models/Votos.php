<?php

namespace app\models;

/**
 * This is the model class for table "votos".
 *
 * @property int $usuario_id
 * @property int $comentario_id
 * @property bool $positivo
 *
 * @property Comentarios $comentario
 * @property Usuarios $usuario
 */
class Votos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'comentario_id', 'positivo'], 'required'],
            [['usuario_id', 'comentario_id'], 'default', 'value' => null],
            [['usuario_id', 'comentario_id'], 'integer'],
            [['positivo'], 'boolean'],
            [['usuario_id', 'comentario_id'], 'unique', 'targetAttribute' => ['usuario_id', 'comentario_id']],
            [['comentario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comentarios::className(), 'targetAttribute' => ['comentario_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Usuario ID',
            'comentario_id' => 'Comentario ID',
            'positivo' => 'Positivo',
        ];
    }

    public function getEsPositivo()
    {
        return $this->positivo;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentario()
    {
        return $this->hasOne(Comentarios::className(), ['id' => 'comentario_id'])->inverseOf('votos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('votos');
    }
}
