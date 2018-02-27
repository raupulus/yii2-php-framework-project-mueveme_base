<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categorias".
 *
 * @property int $id
 * @property string $denominacion
 *
 * @property Envios[] $envios
 */
class Categorias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['denominacion'], 'required'],
            [['denominacion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'denominacion' => 'Denominacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvios()
    {
        return $this->hasMany(Envios::className(), ['categoria_id' => 'id'])->inverseOf('categoria');
    }
}
