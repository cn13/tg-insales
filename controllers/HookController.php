<?php

namespace app\controllers;

use app\helpers\CmdHelper;
use app\helpers\SendCommand;
use app\helpers\SlashCommand;
use app\models\Card;
use app\models\User;
use app\models\UserCard;
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
        //file_put_contents('../runtime/message_n.json', print_r($this->message, 1), FILE_APPEND);
        if (CmdHelper::isCmd($this->message['message']['text'])) {
            CmdHelper::execute($this->message['message']);
        } else {
            $chatId = $this->message['message']['chat']['id'];
            $sender = (new SendCommand());
            if (isset($this->message['message']['contact'])) {
                /** @var Transaction $tr */
                $tr = Card::getDb()->beginTransaction();
                try {
                    /** @var User $user */
                    $user = User::find()->where(['chat_id' => $chatId])->one();
                    if ($user) {
                        if ($user->getCard() === null) {
                            /** @var Card $card */
                            $card = Card::getEmptyCard();
                            $user->setCard($card);
                            $card->genQr();
                        }
                        $sender->sendMessage(
                            $user->chat_id,
                            SlashCommand::mycard($this->message['message'])
                        );
                        $tr->commit();
                        return;
                    }

                    $user_id = md5('tr_' . $chatId . $this->message['message']['contact']['phone_number']);

                    $user = new User(
                        [
                            'phone'   => $this->message['message']['contact']['phone_number'],
                            'name'    => $this->message['message']['contact']['first_name'] ?? '',
                            'chat_id' => (string)$chatId,
                            'user_id' => $user_id
                        ]
                    );
                    $user->save();

                    /** @var Card $card */
                    $card = Card::getEmptyCard();
                    $user->setCard($card);
                    $card->genQr();

                    $phone = $this->message['message']['contact']['phone_number'] ?? '';
                    if (strlen($phone) > 11) {
                        $phone = '';
                    }
                    (new AqsiApi())->createClient(
                        [
                            "id"        => $user_id,
                            "gender"    => 1,
                            "comment"   => (string)("Карта:" . $card->number . " ID:" . $chatId),
                            "fio"       => (string)$this->message['message']['contact']['first_name'],
                            "group"     => [
                                "id" => "dfb6ca32-48b2-4889-98a8-6cebb2ca17cf"
                            ],
                            "birthDate" => date('Y-m-d', strtotime('now -20 year')),
                            "mainPhone" => $phone
                        ]
                    );

                    $sender->sendMessage(
                        $chatId,
                        SlashCommand::mycard($this->message['message'])
                    );

                    $sender->sendMessage(
                        '-1001867486645',
                        'Новый пользователь!' . PHP_EOL .
                        $user->name . ' : ' . $user->phone . PHP_EOL .
                        'Необходимо активировать карту в ЛК: ' . $card->number
                    );

                    $tr->commit();
                } catch (\Throwable $e) {
                    $tr->rollBack();
                    throw $e;
                }
            }

            if ($chatId != '-1001867486645' && \Yii::$app->cache->exists('mail_' . $chatId)) {
                $userName = '';
                if (isset($this->message['message']['chat']['username'])) {
                    $userName = '@' . $this->message['message']['chat']['username'];
                }
                if (!empty($this->message['message']['chat']['first_name'])) {
                    $userName .= ' (' . $this->message['message']['chat']['first_name'] . ')';
                }

                $sender->sendMessage(
                    '-1001867486645',
                    $userName . ':' . PHP_EOL .
                    $this->message['message']['text']
                );

                $sender->sendMessage(
                    $chatId,
                    'Ваше сообщение получено, спасибо!'
                );

                \Yii::$app->cache->delete('mail_' . $chatId);
            }
        }
    }
}