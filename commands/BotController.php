<?php

namespace app\commands;

use app\helpers\SendCommand;
use app\helpers\SlashCommand;
use app\helpers\ViewHelper;
use app\models\User;
use app\models\UserShop;
use app\service\AqsiApi;
use yii\httpclient\Client;

/**
 * @property $token string
 */
class BotController extends \yii\console\Controller
{
    /**
     * @var string|null
     */
    private ?string $hook_url;
    private SendCommand $cmd;

    public function init()
    {
        /* $this->hook_url = \Yii::$app->params['hook_url'];
         $this->cmd = new SendCommand();*/
        parent::init();
    }

    public function actionSendActive()
    {
        $sender = (new SendCommand());
        $users = User::find()->where(['active' => false])->all();
        foreach ($users as $user) {
            $clientAqsi = (new AqsiApi())->getClient($user->user_id);
            $cardNumber = $clientAqsi['loyaltyCard']['number'] ?? null;
            if (!empty($cardNumber)) {
                $user->updateAttributes(['active' => true]);
                $sender->sendMessage(
                    $user->chat_id,
                    SlashCommand::mycard(
                        [
                            'chat' => [
                                'id' => $user->chat_id
                            ]
                        ]
                    )
                );
            }
        }
    }

    public function actionHookList()
    {
        print_r($this->cmd->send('getWebhookInfo'));
    }

    public function actionSetHook()
    {
        print_r($this->cmd->send('setWebhook', ['url' => $this->hook_url]));
    }

    public function actionGetUpdates()
    {
        print_r($this->cmd->send('getUpdates'));
    }

    public function actionTestMessage()
    {
        $result = $this->cmd->send('getUpdates');
        foreach ($result['result'] as $m) {
            print_r(
                $this->cmd->sendMessage(
                    $m['message']['chat']['id'],
                    ViewHelper::view('hello', ['name' => $m['message']['chat']['first_name']])
                )
            );
        }
    }

    public function actionLogin()
    {
        $urlLogin = 'https://lk.aqsi.ru/auth';
        $cookieFile = __DIR__ . '/../runtime/cookie.txt';

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($urlLogin)
            ->setContent('{"emailOrPhone": "cozanostra.me@yandex.ru","password": "usebet051"}')
            ->addHeaders(
                [
                    ":authority:lk.aqsi.ru",
                    ":method: POST",
                    ":path: /auth",
                    ":scheme: https",
                    "accept: application/json, text/plain, */*",
                    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 YaBrowser/22.11.0.2500 Yowser/2.5 Safari/537.36",
                    "origin: https://lk.aqsi.ru",
                    "referer: https://lk.aqsi.ru/login",
                ]
            )->send();
        print_r($response);
    }
}