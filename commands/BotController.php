<?php

namespace app\commands;


class BotController extends \yii\console\Controller
{
    private $token;

    public function init()
    {
        $this->token = \Yii::$app->params['tg_token'];
        parent::init();
    }

    public function actionHookList()
    {
        $url = "https://api.telegram.org/bot{$this->token}/getWebhookInfo";
        $response = file_get_contents($url);
        print_r($response);
    }
}