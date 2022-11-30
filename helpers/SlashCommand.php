<?php

namespace app\helpers;

use app\models\Card;
use chillerlan\QRCode\QRCode;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Contact;

class SlashCommand
{
    /**
     * @param string $cmd
     * @return string
     */
    public static function run(string $cmd, array $m)
    {
        $cmd = preg_replace('#[^a-zA-Z0-9]#', '', $cmd);
        if (method_exists(self::class, $cmd)) {
            return self::$cmd($m);
        }
        return 'Не понял команду!';
    }

    /**
     * @return string
     * @see
     */
    private static function start($message): string
    {
        return 'Привет! Нажми на меню внизу, и получи свою карту лояльности! Скидка 5 процентов, будет увеличиваться, от количества заказов. Условия мы озвучим позже :)';
    }

    private static function info($message): string
    {
        return 'Выполнили ' . __METHOD__;
    }

    /**
     * @param $m
     * @return string
     */
    public static function mail($m)
    {
        $chatId = $message['chat']['id'] ?? '0000';
        \Yii::$app->cache->set('mail_' . $chatId, true, 300);
        return "Напишите ваше сообщение, мы обязательно его получим";
    }

    /**
     * @param $m
     * @return void
     */
    public static function sendMail($m)
    {
        $message = $m['text'];
        $message = wordwrap($message, 70, "\r\n");
        $mailheaders = "MIME-Version: 1.0;\r\n";
        $mailheaders .= "From: bot@smoklife.ru <bot@smoklife.ru>\r\n";
        $mailheaders .= "Reply-To: no-reply@smoklife.ru\r\n";
        mail('m@cn13.ru', 'Запрос из бота SmokeLife', $message, $mailheaders);
    }

    /**
     * @param $message
     * @return string|string[]
     */
    public static function mycard($message)
    {
        $chatId = $message['chat']['id'] ?? '0000';
        $card = Card::find()->where(['chat_id' => $chatId])->one();
        if (!$card) {
            return "Вам необходимо выпустить карту";
        }
        return [
            "photo"   => $card->getQrLink(),
            "caption" => 'Ваша карта лояльности!'
        ];
    }

    /**
     * @return array
     */
    private static function newcard($message)
    {
        $keyboard = [
            'keyboard'          => [
                [
                    [
                        "text"            => "Отправить номер телефона",
                        "request_contact" => true
                    ]
                ]
            ],
            "one_time_keyboard" => true,
            "resize_keyboard"   => true
        ];
        return ['reply_markup' => json_encode($keyboard)];
    }
}