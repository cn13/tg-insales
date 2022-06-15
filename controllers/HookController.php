<?php

namespace app\controllers;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Yii;
use yii\rest\Controller;

class HookController extends Controller
{
    public function actionIndex()
    {
        file_put_contents(
            __DIR__ . DIRECTORY_SEPARATOR . '../runtime/hook.log',
            $this->request->getRawBody(),
            FILE_APPEND
        );
        syslog(LOG_NOTICE, 'MESSAGE');
        $m = json_decode($this->request->getRawBody());

        $bot = new BotApi(Yii::$app->params['tg_token']);

        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    ['text' => 'link', 'url' => 'https://core.telegram.org']
                ]
            ]
        );

        $bot->sendMessage($m['message']['chat']['id'], "Принято, работаем", null, false, null, $keyboard);

        return $this->response;
    }
}