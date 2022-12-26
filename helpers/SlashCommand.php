<?php

namespace app\helpers;

use app\models\Card;
use app\models\Good;
use app\models\User;

class SlashCommand
{
    /**
     * @param string $cmd
     * @return string
     */
    public static function run(string $cmd, array $m)
    {
        if (method_exists(self::class, preg_replace('#[^a-zA-Z0-9]#', '', $cmd))) {
            return self::$cmd($m);
        }
        preg_match('#^(setbar)(.+?)$#', preg_replace('#[^a-zA-Z0-9]#', '', $cmd), $out);
        if (isset($out[1], $out[2]) && $out[1] === 'setbar' && !empty($out[2])) {
            return self::setbar($m, $out[2]);
        }

        return 'Не понял команду!';
    }

    private static function setbar($m, $id)
    {
        $chatId = $m['chat']['id'] ?? '0000';
        \Yii::$app->cache->set('setbar_' . $chatId, $id, 300);
        $model = Good::find()->where(['id' => $id])->one();
        return "Введите штрихкод для " . $model->name . " :";
    }

    /**
     * @return string
     * @see
     */
    private static function start($message): string
    {
        return 'Привет! Нажми на меню внизу, и получи свою карту лояльности! Скидка 5 процентов, будет увеличиваться, от суммы заказов.';
    }

    private static function info($message): string
    {
        return 'Выполнили ' . __METHOD__;
    }

    /**
     * @param $m
     * @return string
     */
    public static function search($m)
    {
        $chatId = $m['chat']['id'] ?? '0000';
        \Yii::$app->cache->set('search_' . $chatId, true, 300);
        return "Что ищем?";
    }

    /**
     * @param $m
     * @return string
     */
    public static function shift($m)
    {
        return Shifts::get();
    }

    /**
     * @param $m
     * @return string
     */
    public static function mail($m)
    {
        $chatId = $m['chat']['id'] ?? '0000';
        \Yii::$app->cache->set('mail_' . $chatId, true, 300);
        return "Напишите ваше сообщение, мы обязательно его получим";
    }

    /**
     * @param $message
     * @return string|string[]
     */
    public static function mycard($message)
    {
        $chatId = $message['chat']['id'] ?? '0000';
        $user = User::find()->where(['chat_id' => $chatId])->one();
        if (!$user) {
            return "Вам необходимо выпустить карту";
        }
        /** @var Card $card */
        $card = $user->getCard();
        if ($card === null) {
            return "Вам необходимо выпустить карту";
        }

        $clientAqsi = ClientHelper::get($user->aqsi_id);
        $cardNumber = $clientAqsi['loyaltyCard']['number'] ?? null;
        if (empty($cardNumber) || $user->active === 0) {
            return "Ваша карта скоро будет активирована.";
        }


        return [
            "photo" => $card->getQrLink(),
            "caption" => sprintf(
                "Ваша карта лояльности!  Скидка %s%%\r\n\r\nВы сделали покупок на сумму: %s руб.\r\n\r\n от 0 руб. - 5%% \r\n от 7000 руб. - 7%% \r\n от 15000 руб. - 10%%",
                $card->value,
                $user->amount ?? 0
            )
        ];
    }

    /**
     * @return array
     */
    private static function newcard($message)
    {
        $keyboard = [
            'keyboard' => [
                [
                    [
                        "text" => "Отправить номер телефона",
                        "request_contact" => true
                    ]
                ]
            ],
            "one_time_keyboard" => true,
            "resize_keyboard" => true
        ];
        return ['reply_markup' => json_encode($keyboard)];
    }
}