<?php

namespace app\commands;

use app\helpers\SendCommand;

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