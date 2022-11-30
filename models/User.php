<?php

namespace app\models;

use chillerlan\QRCode\QRCode;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_shop".
 *
 * @property int         $id
 * @property string      $chat_id
 * @property string      $name
 * @property string      $phone
 * @property string      $user_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['created_at', 'updated_at', 'phone', 'chat_id', 'name', 'user_id'], 'safe'],
        ];
    }

    /**
     * @param Card $card
     * @return void
     */
    public function setCard(Card $card): void
    {
        UserCard::updateAll(['active' => 0], ['user_id' => $this->id]);
        $model = UserCard::find()->where(['user_id' => $this->id, 'card_id' => $card->id])->one();
        if (empty($model)) {
            $model = new UserCard(
                [
                    'user_id' => $this->id,
                    'card_id' => $card->id
                ]
            );
            $model->save();
        } else {
            $model->active();
        }
    }

    /**
     * @return Card|null
     */
    public function getCard()
    {
        $userCard = UserCard::find()->where(['user_id' => $this->id, 'active' => 1])->one();
        return Card::findOne($userCard->card_id);
    }
}
