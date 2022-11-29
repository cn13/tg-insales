<?php

namespace app\controllers;

use app\helpers\CmdHelper;
use app\helpers\SendCommand;
use app\helpers\ViewHelper;
use app\models\UserShop;
use yii\rest\Controller;

class HookController extends Controller
{
    private SendCommand $cmd;

    private array $message;

    public function init()
    {
        parent::init();
        $this->message = json_decode($this->request->getRawBody(), true);
    }

    public function actionIndex()
    {
        if (CmdHelper::isCmd($this->message['message']['text'])) {
            CmdHelper::execute($this->message);
        } else {
            CmdHelper::index($this->message);
        }
    }
}