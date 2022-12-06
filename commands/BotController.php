<?php

namespace app\commands;

use app\helpers\SendCommand;
use app\helpers\SlashCommand;
use app\helpers\ViewHelper;
use app\models\Card;
use app\models\User;
use app\models\UserShop;
use app\service\AqsiApi;

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
        /* $this->hook_url = \Yii::$app->params['hook_url'];
         $this->cmd = new SendCommand();*/
        parent::init();
    }

    public function actionSendActive()
    {
        $prefix = [
            2022 => 5,
            3022 => 7,
            5022 => 10,
            7777 => 15,
        ];

        $sender = (new SendCommand());
        $users = User::find()->all();
        foreach ($users as $user) {
            $clientAqsi = (new AqsiApi())->getClient($user->user_id);
            $cardNumber = $clientAqsi['loyaltyCard']['number'] ?? null;
            if (!empty($cardNumber) && $user->active === false) {
                $user->updateAttributes(['active' => true]);
                $sender->sendMessage(
                    $user->chat_id,
                    SlashCommand::mycard(
                        [
                            'chat' => [
                                'id' => $user->chat_id
                            ]
                        ]
                    )
                );
            }

            if ($user->active === true) {
                $cardNumber = trim($clientAqsi['loyaltyCard']['prefix'] . $clientAqsi['loyaltyCard']['number']);
                $cardInDB = $user->getCard()->number;
                if ($cardNumber !== $cardInDB) {
                    $card = Card::find()->where(['number' => $cardNumber])->one();
                    if (!$card) {
                        $card = new Card(
                            [
                                'number' => $cardNumber,
                                'value' => $prefix[$clientAqsi['loyaltyCard']['prefix']]
                            ]
                        );
                        $card->save();
                    }
                    $user->setCard($card);
                    $sender->sendMessage(
                        $user->chat_id,
                        SlashCommand::mycard(
                            [
                                'chat' => [
                                    'id' => $user->chat_id
                                ]
                            ]
                        )
                    );
                }
            }
        }
    }

    public function actionHookList()
    {
        print_r($this->cmd->send('getWebhookInfo'));
    }

    public function actionSetHook()
    {
        print_r($this->cmd->send('setWebhook', ['url' => $this->hook_url]));
    }

    public function actionGetUpdates()
    {
        print_r($this->cmd->send('getUpdates'));
    }

    public function actionTestMessage()
    {
        $result = $this->cmd->send('getUpdates');
        foreach ($result['result'] as $m) {
            print_r(
                $this->cmd->sendMessage(
                    $m['message']['chat']['id'],
                    ViewHelper::view('hello', ['name' => $m['message']['chat']['first_name']])
                )
            );
        }
    }

    public function actionCheck()
    {
        $sender = new SendCommand();
        $rootDir = __DIR__ . '/../runtime/check';
        if (!file_exists($rootDir) && !mkdir($rootDir) && !is_dir($rootDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $rootDir));
        }
        chmod($rootDir, 0777);
        $checkDir = $rootDir . '/' . date('Y-m-d');
        if (!file_exists($checkDir) && !mkdir($checkDir) && !is_dir($checkDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $checkDir));
        }
        chmod($checkDir, 0777);

        $result = (new AqsiApi())->getReceipts(
            [
                'filtered.BeginDate' => date('Y-m-d 00:00:00'),
                'filtered.Operation' => 1,
                'pageSize' => 100
            ]
        );

        if (empty($result['rows'])) {
            \Yii::$app->end();
        }

        foreach ($result['rows'] as $check) {
            if (file_exists($checkDir . DIRECTORY_SEPARATOR . $check['id'])) {
                continue;
            }


            $text = 'НОВЫЙ ЧЕК ' . PHP_EOL;
            $text .= '----' . PHP_EOL;
            foreach ($check['content']['positions'] as $position) {
                $text .= $position['text'] . ' - ' . $position['price'] . ' руб.' . PHP_EOL;
            }
            $text .= '----' . PHP_EOL;
            $text .= 'ИТОГО: ' . $check['amount'] . ' руб.' . PHP_EOL;
            $text .= PHP_EOL;

            $sender->sendMessage(
                '-873525534',
                $text
            );

            file_put_contents($checkDir . DIRECTORY_SEPARATOR . $check['id'], 1);
        }
    }
}