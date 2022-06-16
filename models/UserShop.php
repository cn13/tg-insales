<?php

namespace app\models;

/**
 * This is the model class for table "user_shop".
 *
 * @property int $id
 * @property string $shop
 * @property string $token
 * @property string $insales_id
 * @property string $access_token
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class UserShop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop', 'token', 'insales_id'], 'required'],
            [['created_at', 'updated_at', 'access_token'], 'safe'],
            [['shop', 'token', 'insales_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop' => 'Shop',
            'token' => 'Token',
            'insales_id' => 'Insales ID',
            'access_token' => 'access_token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getAccessToken()
    {
        return md5($this->token . \Yii::$app->params['tg_secret']);
    }

    public function api($method = '/admin/orders/count.json', $params = [])
    {
        $app = \Yii::$app->params['tg_login'];
        $url = "http://{$app}:{$this->getAccessToken()}@{$this->shop}/" . ltrim($method, '/');
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return file_get_contents($url);
    }
}
