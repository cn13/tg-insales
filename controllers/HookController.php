<?php

namespace app\controllers;

use app\helpers\SendCommand;
use app\models\UserShop;
use yii\rest\Controller;

class HookController extends Controller
{
    private SendCommand $cmd;

    public function init()
    {
        $this->cmd = new SendCommand();
        parent::init();
    }

    public function actionIndex()
    {
        $m = json_decode($this->request->getRawBody(), true);
        syslog(LOG_NOTICE, print_r($m, 1));
        syslog(LOG_NOTICE, "SEND");
        try {
            $user = UserShop::findOne(['tg_username' => $m['message']['chat']['username']]);
            if (!empty($user)) {
                $message = $this->view('hello', [
                    'name' => $m['message']['chat']['first_name'],
                    'shopUrl' => $user->shop
                ]);
                $user->updateAttributes(['tg_chat_id' => $m['message']['chat']['id']]);
            } else {
                $message = $this->view('shop_not_found');
            }

            $result = $this->cmd->sendMessage(
                $m['message']['chat']['id'],
                $message
            );
            syslog(LOG_NOTICE, print_r($result, 1));
        } catch (\Throwable $e) {
            syslog(LOG_NOTICE, $e->getMessage());
        }
        syslog(LOG_NOTICE, "END");
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    private function view(string $name, array $params = []): string
    {
        return $this->renderFile(
            __DIR__ . "/../views/message/$name.php",
            $params
        );
    }
}