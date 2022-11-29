<?php

namespace app\models;

/**
 * This is the model class for table "user_shop".
 *
 * @property int         $id
 * @property string      $chat_id
 * @property string      $number
 * @property string      $phone
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number'], 'required'],
            [['created_at', 'updated_at', 'phone', 'chat_id'], 'safe'],
        ];
    }
}
