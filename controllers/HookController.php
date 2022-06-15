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
        $m = json_decode($this->request->getRawBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->cmd->sendMessage(
            $m['message']['chat']['id'],
            $this->view('hello', ['name' => $m['message']['chat']['first_name']])
        );
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