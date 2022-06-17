<?php

namespace app\helpers;

use app\models\UserShop;

class CmdHelper
{
    /**
     * @param string $message
     * @return bool
     */
    public static function isCmd(string $message): bool
    {
        $message = trim($message);
        return $message[0] === '/';
    }

    /**
     * @param mixed $m
     * @return void
     */
    public static function index(mixed $m)
    {
        try {
            $cmd = new SendCommand();
            $user = UserShop::findOne(['tg_username' => $m['message']['chat']['username']]);
            if (!empty($user)) {
                $message = ViewHelper::view('hello', [
                    'name' => $m['message']['chat']['first_name'],
                    'shopUrl' => $user->shop
                ]);
                $user->updateAttributes(['tg_chat_id' => $m['message']['chat']['id']]);
            } else {
                $message = ViewHelper::view('shop_not_found');
            }

            $cmd->sendMessage(
                $m['message']['chat']['id'],
                $message
            );
        } catch (\Throwable $e) {
            syslog(LOG_NOTICE, $e->getMessage());
        }
    }

    /**
     * @param mixed $m
     * @return void
     * @throws \JsonException
     */
    public static function execute(mixed $m)
    {
        $cmd = new SendCommand();
        $cmd->sendMessage(
            $m['message']['chat']['id'],
            "Выполняем " . $m['message']['text']
        );
    }
}