<?php

namespace app\models;

use chillerlan\QRCode\QRCode;

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

    /**
     * @return void
     */
    public function genQr(): void
    {
        $path = \Yii::$app->basePath . "/web/" . $this->getPathFile();
        if (!file_exists($path) && !mkdir($path) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        (new QRCode())->render($this->number, $path . '/card.png');
    }

    /**
     * @return string
     */
    public function getQrLink()
    {
        return 'https://api.smokelife.ru/' . $this->getPathFile() . '/card.png';
    }

    /**
     * @return string
     */
    private function getPathFile()
    {
        return "/gen/" . $this->chat_id . '_05';
    }
}
