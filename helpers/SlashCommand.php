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