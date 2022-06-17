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
        $this->message = json_decode($this->request->getRawBody(), true);
        parent::init();
    }

    public function actionIndex()
    {
        if (CmdHelper::isCmd($this->message['message']['text'])) {
            CmdHelper::execute($this->message);
        } else {
            CmdHelper::index($this->message);
        }
    }

    /**
     * @return void
     */
    public function actionOrderCreate()
    {
        $id = \Yii::$app->request->getQueryParam('id');
        $user = UserShop::findOne($id);
        $message = ViewHelper::view('order_new', ['order' => $this->message, 'user' => $user]);
        $this->sendMessage($user, $message);
    }

    /**
     * Обновление заказа
     * @return void
     */
    public function actionOrderUpdate()
    {
        $id = \Yii::$app->request->getQueryParam('id');
        $user = UserShop::findOne($id);
        $message = ViewHelper::view('order_update', ['order' => $this->message, 'user' => $user]);
        $this->sendMessage($user, $message);
    }

    /**
     * @param UserShop $user
     * @param string $message
     * @return void
     */
    private function sendMessage(UserShop $user, string $message)
    {
        try {
            (new SendCommand())->sendMessage(
                $user->tg_chat_id,
                $message
            );
        } catch (\Throwable $e) {
            syslog(LOG_NOTICE, $e->getMessage());
        }
    }
}