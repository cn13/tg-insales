<?php

namespace app\controllers;

use app\helpers\CmdHelper;
use app\helpers\SendCommand;
use app\helpers\SlashCommand;
use app\models\Card;
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
        file_put_contents(
            '../runtime/messages_new.json',
            print_r($this->message['message'], true) .
            PHP_EOL . PHP_EOL,
            FILE_APPEND
        );
        if (CmdHelper::isCmd($this->message['message']['text'])) {
            CmdHelper::execute($this->message['message']);
        } else {
            if (isset($this->message['message']['contact'])) {
                $chatId = $this->message['message']['chat']['id'];
                $card = Card::find()->where(['chat_id' => $chatId])->one();
                if ($card) {
                    (new SendCommand())->sendMessage(
                        $card->chat_id,
                        SlashCommand::mycard($this->message['message'])
                    );
                    return;
                }

                $card = Card::find()->where("chat_id = ''")->one();

                $card->updateAttributes(
                    [
                        'phone'   => $this->message['message']['contact']['phone_number'],
                        'chat_id' => $chatId
                    ]
                )

                (
                    new AqsiApi()
                )->createClient(
                        [
                            "id"        => "tg_" . $chatId,
                            "gender"    => 1,
                            "comment"   => $card->number,
                            "fio"       => $this->message['message']['contact']['first_name'],
                            "group"     => [
                                "id" => "0aa6dac6-73ce-4753-98fd-65ba4f9a3764"
                            ],
                            "mainPhone" => $this->message['message']['contact']['phone_number'],
                        ]
                    );

                $path = \Yii::$app->basePath . "/web/gen/" . $chatId;
                if (!file_exists($path) && !mkdir($path) && !is_dir($path)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
                }
                (new QRCode())->render($chatId, $path . '/card.png');

                (new SendCommand())->sendMessage(
                    $chatId,
                    SlashCommand::mycard($this->message['message'])
                );
            }
        }
    }
}