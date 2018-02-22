<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $password
 * @property string $email
 * @property string $authkey
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comentarios[] $comentarios
 * @property Envios[] $envios
 * @property Movimientos[] $movimientos
 * @property Envios[] $envios0
 */
class Usuarios extends \yii\db\ActiveRecord
{
    /**
     * COntendra la información del campo confirmar contraseña del formulario
     * de lata y modificación
     * @var string
     */
    public $password_repeat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'password','password_repeat', 'email'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre', 'password', 'email','password_repeat'], 'string', 'max' => 255],
            [
                ['password_repeat'],
                'compare',
                'compareAttribute' => 'password',
            ],
            [['email'], 'unique'],
            [['nombre'], 'unique'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['password_repeat']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'password' => 'contraseña',
            'email' => 'Email',
            'created_at' => 'Creado',
            'updated_at' => 'Modificado',
            'password_repeat' => 'Confirmar contraseña',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Comprueba si la contraseña indicada es la contraseña del usuario.
     * @param  string $password La contraseña.
     * @return bool             Si es una contraseña válida o no.
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(
            $password,
            $this->password
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvios()
    {
        return $this->hasMany(Envios::className(), ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovimientos()
    {
        return $this->hasMany(Movimientos::className(), ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvios0()
    {
        return $this->hasMany(Envios::className(), ['id' => 'envio_id'])->viaTable('movimientos', ['usuario_id' => 'id']);
    }

    public function beforeSave($insert){
        if ($insert) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
            //$this->authKey = Yii::$app->security->generateRandomString();
            return true;
        }
        return false;
    }
}
