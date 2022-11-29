<?php

namespace app\controllers;

use app\helpers\CmdHelper;
use app\helpers\SendCommand;
use app\helpers\SlashCommand;
use app\helpers\ViewHelper;
use app\models\UserShop;
use app\service\AqsiApi;
use chillerlan\QRCode\QRCode;
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
        //file_put_contents('../runtime/messages.json', json_encode($this->message), FILE_APPEND);
        if (CmdHelper::isCmd($this->message['message']['text'])) {
            CmdHelper::execute($this->message);
        } else {
            if (isset($this->message['message']['contact'])) {
                $chatId = $this->message['message']['chat']['id'];
                (new AqsiApi())->createClient(
                    [
                        "id"        => $chatId,
                        "fio"       => $this->message['message']['contact']['first_name'],
                        "group"     => [
                            "id" => "99f92a69-8fb8-4c69-947b-325528305ef6"
                        ],
                        "mainPhone" => $this->message['message']['contact']['phone'],
                    ]
                );
                $path = \Yii::$app->basePath . "/web/gen/" . $chatId;
                (new QRCode())->render($chatId, $path . '/card.png');
                SlashCommand::mycard($this->message);
            } else {
                CmdHelper::index($this->message);
            }
        }
    }
}