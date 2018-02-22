<?php

namespace app\models;

/**
 * This is the model class for table "comentarios".
 *
 * @property int $id
 * @property string $texto
 * @property int $envio_id
 * @property int $usuario_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Envios $envio
 * @property Usuarios $usuario
 */
class Comentarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comentarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['texto', 'envio_id', 'usuario_id'], 'required'],
            [['envio_id', 'usuario_id'], 'default', 'value' => null],
            [['envio_id', 'usuario_id'], 'filter', 'filter' => 'intval'],
            [['envio_id', 'usuario_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['texto'], 'string', 'max' => 255],
            [['envio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envios::className(), 'targetAttribute' => ['envio_id' => 'id']],
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
            'texto' => 'Añadir comentario',
            'envio_id' => 'Envio ID',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvio()
    {
        return $this->hasOne(Envios::className(), ['id' => 'envio_id'])->inverseOf('comentarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('comentarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotos()
    {
        return $this->hasMany(Votos::className(), ['comentario_id' => 'id'])->inverseOf('comentario');
    }
}
