<?php

namespace app\models;

/**
 * This is the model class for table "respuestas".
 *
 * @property int $id
 * @property string $texto
 * @property int $comentario_id
 * @property int $usuario_id
 * @property string $created_at
 *
 * @property Comentarios $comentario
 * @property Usuarios $usuario
 */
class Respuestas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'respuestas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['texto', 'comentario_id', 'usuario_id'], 'required', 'message' => 'No puede estar vacio'],
            [['comentario_id', 'usuario_id'], 'default', 'value' => null],
            [['comentario_id', 'usuario_id'], 'filter', 'filter' => 'intval'],
            [['comentario_id', 'usuario_id'], 'integer'],
            [['created_at'], 'safe'],
            [['texto'], 'string', 'max' => 255],
            [['comentario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comentarios::className(), 'targetAttribute' => ['comentario_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'texto' => 'Añadir una respuesta',
            'comentario_id' => 'Comentario ID',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentario()
    {
        return $this->hasOne(Comentarios::className(), ['id' => 'comentario_id'])->inverseOf('respuestas');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('respuestas');
    }
}
