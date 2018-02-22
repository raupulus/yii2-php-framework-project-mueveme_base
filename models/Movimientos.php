<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "movimientos".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $envio_id
 * @property string $created_at
 *
 * @property Envios $envio
 * @property Usuarios $usuario
 */
class Movimientos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'envio_id'], 'required'],
            [['usuario_id', 'envio_id'], 'default', 'value' => null],
            [['usuario_id', 'envio_id'], 'integer'],
            [['created_at'], 'safe'],
            [['envio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Envios::className(), 'targetAttribute' => ['envio_id' => 'id']],
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
            'usuario_id' => 'Usuario ID',
            'envio_id' => 'Envio ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvio()
    {
        return $this->hasOne(Envios::className(), ['id' => 'envio_id'])->inverseOf('movimientos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('movimientos');
    }
}
