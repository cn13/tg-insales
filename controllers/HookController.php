<?php

namespace app\controllers;

use app\helpers\SendCommand;
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
            $result = $this->cmd->sendMessage(
                $m['message']['chat']['id'],
                $this->view('hello', ['name' => $m['message']['chat']['first_name']])
            );
            syslog(LOG_NOTICE, print_r($result, 1));
        } catch (\Throwable $e) {
            syslog(LOG_NOTICE, $e->getMessage());
        }
        syslog(LOG_NOTICE, "END");
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