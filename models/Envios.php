<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 * @property Usuarios[] $usuarios
 */
class Envios extends \yii\db\ActiveRecord
{
    public $numEnvios;
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
            [['url', 'titulo', 'entradilla'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['url', 'titulo', 'entradilla'], 'string', 'max' => 255],
            [['url'], 'url'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new Expression('NOW()'),
            ],
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
            'titulo' => 'Titulo',
            'numEnvios' => 'Número de movimientos',
            'entradilla' => 'Entradilla',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'usuario_id'])->viaTable('movimientos', ['envio_id' => 'id']);
    }

    public function getNumEnvios()
    {
        return $this->getMovimientos()->count();
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->usuario_id = Yii::$app->user->identity->id;
            }
            return true;
        }
        return false;
    }
}
