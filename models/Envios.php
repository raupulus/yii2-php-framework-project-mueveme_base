<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "envios".
 *
 * @property int $id
 * @property string $url
 * @property string $entradilla
 * @property int $usuario_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Usuarios $usuario
 * @property Movimientos[] $movimientos
 * @property Usuarios[] $usuarios
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
            [['url', 'entradilla', 'usuario_id'], 'required'],
            [['entradilla'], 'string'],
            [['usuario_id'], 'default', 'value' => null],
            [['usuario_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
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
            'url' => 'Url',
            'entradilla' => 'Entradilla',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'usuario_id'])->viaTable('movimientos', ['envio_id' => 'id']);
    }
}
