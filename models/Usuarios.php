<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comentarios[] $comentarios
 * @property Envios[] $envios
 * @property Movimientos[] $movimientos
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $passwordRepeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['passwordRepeat']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'email', 'password', 'passwordRepeat'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre', 'email', 'password'], 'string', 'max' => 255],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password'],
            [['nombre'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre de usuario',
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'passwordRepeat' => 'Confirmar contraseña',
        ];
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
         // return $this->authKey;
    }

     public function validateAuthKey($authKey)
     {
         // return $this->authKey === $authKey;
     }

     public function validatePassword($password)
     {
         return Yii::$app->security->validatePassword(
             $password,
             $this->password
         );
     }

     public function beforeSave($insert)
     {
         if (parent::beforeSave($insert)) {
             if ($insert) {
                 $this->password = Yii::$app->security->generatePasswordHash($this->password);
                 $this->created_at = date('Y-m-d H:i:s');
             }
             return true;
         }
         return false;
     }
}
