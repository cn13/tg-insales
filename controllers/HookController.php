<?php

namespace app\controllers;

use app\helpers\CmdHelper;
use app\helpers\SendCommand;
use app\helpers\SlashCommand;
use app\models\Card;
use app\service\AqsiApi;
use yii\db\Transaction;
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

    /**
     * @throws \JsonException
     * @throws \Exception
     * @throws \Throwable
     */
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
                /** @var Transaction $tr */
                $tr = Card::getDb()->beginTransaction();
                try {
                    $chatId = $this->message['message']['chat']['id'];
                    $card = Card::find()->where(['chat_id' => $chatId])->one();
                    if ($card) {
                        (new SendCommand())->sendMessage(
                            $card->chat_id,
                            SlashCommand::mycard($this->message['message'])
                        );
                        return;
                    }

                    $card = Card::find()->where("chat_id is null")->one();

                    $user_id = md5($chatId . $this->message['message']['contact']['phone_number']);
                    (new AqsiApi())->createClient(
                        [
                            "id"          => $user_id,
                            "gender"      => 1,
                            "comment"     => (string)$card->number,
                            "loyaltyCard" => [
                                "prefix" => substr($card->number, 0, 2),
                                "number" => substr($card->number, 2, 4),
                            ],
                            "fio"         => (string)$this->message['message']['contact']['first_name'],
                            "group"       => [
                                "id" => (string)"0aa6dac6-73ce-4753-98fd-65ba4f9a3764"
                            ],
                            "mainPhone"   => (string)$this->message['message']['contact']['phone_number'],
                        ]
                    );

                    $card->updateAttributes(
                        [
                            'phone'   => (string)$this->message['message']['contact']['phone_number'],
                            'chat_id' => (string)$chatId,
                            'user_id' => $user_id
                        ]
                    );
                    $card->genQr();

                    (new SendCommand())->sendMessage(
                        $chatId,
                        SlashCommand::mycard($this->message['message'])
                    );
                    $tr->commit();
                } catch (\Throwable $e) {
                    $tr->rollBack();
                    throw $e;
                }
            }
        }
    }
}