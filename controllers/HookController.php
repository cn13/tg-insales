<?php

namespace app\controllers;

use TelegramBot\Api\BotApi;
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

        $bot = new BotApi(Yii::$app->params['tg_token']);

        $m = print_r(json_decode($this->request->getRawBody(), true), 1);
        syslog(LOG_NOTICE, $m);
        //$bot->sendMessage($m['message']['chat']['id'], "Принято, работаем");

        return $this->response;
    }
}