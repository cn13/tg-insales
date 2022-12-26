<?php

namespace app\models;

use app\helpers\CardHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_shop".
 *
 * @property int $id
 * @property string $chat_id
 * @property string $name
 * @property string $phone
 * @property int $account_id
 * @property int $active
 * @property int $receipts_count
 * @property string $amount
 * @property string $user_id
 * @property string $aqsi_id
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
            [['phone', 'chat_id'], 'required'],
            [
                [
                    'created_at',
                    'updated_at',
                    'phone',
                    'chat_id',
                    'amount',
                    'receipts_count',
                    'name',
                    'user_id',
                    'account_id',
                    'aqsi_id',
                    'active'
                ],
                'safe'
            ],
        ];
    }

    /**
     * @param Card $card
     * @return void
     */
    public function setCard(Card $card): void
    {
        UserCard::updateAll(['active' => 0], ['user_id' => $this->id]);
        $model = new UserCard(
            [
                'user_id' => $this->id,
                'card_id' => $card->id,
                'active' => 1
            ]
        );
        if (!$model->save()) {
            throw new \Exception(print_r($model->getErrors(), 1));
        }
    }

    /**
     * @return Card|null
     */
    public function getCard()
    {
        $userCard = UserCard::find()->where(['user_id' => $this->id, 'active' => 1])->one();
        if ($userCard) {
            return Card::find()->where(['id' => $userCard->card_id])->one();
        }
        return null;
    }
}
