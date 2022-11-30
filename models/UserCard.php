<?php

namespace app\models;

use chillerlan\QRCode\QRCode;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_card".
 *
 * @property int         $active
 * @property int         $user_id
 * @property int         $card_id
 * @property string|null $created_at
 */
class UserCard extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'card_id', 'active'], 'required'],
            [['created_at', 'card_id', 'active', 'user_id'], 'safe'],
        ];
    }

    /**
     * @return void
     */
    public function active()
    {
        $this->updateAttributes(['active' => 1]);
    }
}
