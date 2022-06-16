<?php

namespace app\commands;

use app\helpers\SendCommand;
use app\models\UserShop;

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
                    $this->view('hello', ['name' => $m['message']['chat']['first_name']])
                )
            );
        }
    }

    public function actionTest()
    {
        $user = UserShop::findOne(2);
        $user->api(
            '/admin/webhooks.json',
            [
                'webhook' => [
                    "address" => "https://cn13.ru/index.php?r=hook/order-create&id={$user->id}",
                    "topic" => "orders/create",
                    "format_type" => "json"
                ]
            ],
            true
        );

        $user->api(
            '/admin/webhooks.json',
            [
                'webhook' => [
                    "address" => "https://cn13.ru/index.php?r=hook/order-update&id={$user->id}",
                    "topic" => "orders/update",
                    "format_type" => "json"
                ]
            ],
            true
        );

        echo $user->api('/admin/webhooks.json');
    }

    /**
     * @param $name
     * @param $params
     * @return string
     */
    private function view($name, $params): string
    {
        return $this->renderFile(
            __DIR__ . "/../views/message/$name.php",
            $params
        );
    }
}