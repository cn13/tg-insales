<?php

namespace app\commands;


/**
 * @property $token string
 */
class BotController extends \yii\console\Controller
{
    /**
     * @var string|null
     */
    private ?string $token;
    private ?string $hook_url;

    public function init()
    {
        $this->token = \Yii::$app->params['tg_token'];
        $this->hook_url = \Yii::$app->params['hook_url'];
        parent::init();
    }

    public function actionHookList()
    {
        $url = "https://api.telegram.org/bot{$this->token}/getWebhookInfo";
        $response = file_get_contents($url);
        print_r($response);
        echo PHP_EOL;
    }

    public function actionSetHook()
    {
        $url = "https://api.telegram.org/bot{$this->token}/setWebhook?url={$this->hook_url}";
        $response = file_get_contents($url);
        print_r($response);
        echo PHP_EOL;
    }

    public function actionGetUpdates()
    {
        $url = "https://api.telegram.org/bot{$this->token}/getUpdates";
        $response = file_get_contents($url);
        print_r($response);
        echo PHP_EOL;
    }
}