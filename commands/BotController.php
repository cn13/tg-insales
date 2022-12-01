<?php

namespace app\commands;

use app\helpers\SendCommand;
use app\helpers\SlashCommand;
use app\helpers\ViewHelper;
use app\models\UserShop;
use app\service\AqsiApi;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

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
        $this->hook_url = \Yii::$app->params['hook_url'];
        $this->cmd = new SendCommand();
        parent::init();
    }

    public function actionQr()
    {
        $clientAqsi = (new AqsiApi())->getClient('251b25d6b973017e82e2cea3b3173639');
        $cardNumber = $clientAqsi['loyaltyCard']['number'] ?? null;
        if(empty($cardNumber)){

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

    public function actionTest()
    {
        $user = UserShop::findOne(1);
        echo SlashCommand::run($user, '\start');
    }
}