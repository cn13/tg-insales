<?php

namespace app\models;

use chillerlan\QRCode\QRCode;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_shop".
 *
 * @property int $id
 * @property string $number
 * @property int $value
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Card extends ActiveRecord
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
            [['created_at', 'updated_at', 'value', 'id'], 'safe'],
        ];
    }

    /**
     * @return void
     */
    public function genQr(): void
    {
        $path = \Yii::$app->basePath . "/web/cards/";
        if (!file_exists($path) && !mkdir($path) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        if (!file_exists($path . '/' . $this->number . '.png')) {
            (new QRCode())->render($this->number, $path . '/' . $this->number . '.png');
        }
    }

    /**
     * @return string
     */
    public function getQrLink()
    {
        $this->genQr();
        return 'https://api.smokelife.ru/cards/' . $this->number . '.png';
    }

    /**
     * @param int $value
     * @return array|ActiveRecord|null
     */
    public static function getEmptyCard(int $value = 5)
    {
        return Card::find()
            ->leftJoin(UserCard::tableName(), 'card.id = user_card.card_id')
            ->where('user_card.card_id is null')
            ->andWhere(['card.value' => $value])
            ->orderBy('number')
            ->one();
    }
}
