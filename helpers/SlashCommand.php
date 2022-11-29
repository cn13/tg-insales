<?php

namespace app\helpers;

use chillerlan\QRCode\QRCode;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Contact;

class SlashCommand
{
    /**
     * @param string $cmd
     * @return string
     */
    public static function run(string $cmd)
    {
        $cmd = preg_replace('#[^a-zA-Z0-9]#', '', $cmd);
        if (method_exists(self::class, $cmd)) {
            return self::$cmd();
        }
        return 'Не понял команду!';
    }

    /**
     * @return string
     * @see
     */
    private static function start(): string
    {
        return 'Выполнили ' . __METHOD__;
    }

    private static function info(): string
    {
        return 'Выполнили ' . __METHOD__;
    }

    /**
     * @return string[]
     * @throws \Exception
     */
    private static function discount()
    {
        $n = random_int(3, 7);
        $image = \Yii::$app->basePath . "/web/gen/" . $n . ".png";
        (new QRCode())->render($n . '%', $image);
        return [
            "photo"   => 'https://api.smokelife.ru/gen/' . $n . '.png',
            "caption" => 'Скидка готова, покажите QR код на кассе!'
        ];
    }

    /**
     * @return bool
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    public static function newcard($message)
    {
        $bot = new BotApi(\Yii::$app->params['tg_token']);
        $keyboard = new Contact(); // true for one-time keyboard
        $bot->sendMessage(
            $message['message']['chat']['id'],
            'Необходим доступ к вашему номеру.',
            null,
            false,
            null,
            $keyboard
        );
        return true;
    }
}