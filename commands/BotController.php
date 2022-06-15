<?php

namespace app\commands;

use app\helpers\SendCommand;
use TelegramBot\Api\BotApi;

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
    private SendCommand $cmd;

    public function init()
    {
        $this->token = \Yii::$app->params['tg_token'];
        $this->hook_url = \Yii::$app->params['hook_url'];
        $this->cmd = new SendCommand();
        parent::init();
    }

    public function actionHookList()
    {
        $this->cmd->send('getWebhookInfo');
    }

    public function actionSetHook()
    {
        $this->cmd->send('setWebhook', ['url' => $this->hook_url]);
    }

    public function actionGetUpdates()
    {
        $this->cmd->send('getUpdates');
    }

    public function actionTestMessage()
    {
        $bot = new BotApi(\Yii::$app->params['tg_token']);
        $result = $this->cmd->send('getUpdates');
        foreach ($result['result'] as $m) {
            print_r($m);
            $bot->sendMessage('#' . $m['message']['chat']['id'], "Принято, работаем");
        }
    }
}