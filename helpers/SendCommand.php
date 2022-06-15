<?php

namespace app\helpers;

class SendCommand
{
    public mixed $token;

    public function __construct()
    {
        $this->token = \Yii::$app->params['tg_token'];
    }

    /**
     * @throws \JsonException
     */
    public function send(string $cmd, array $params = []): array
    {
        $url = "https://api.telegram.org/bot{$this->token}/$cmd";
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return json_decode(file_get_contents($url), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    public function sendMessage($id, $text): array
    {
        return $this->send(
            'sendMessage',
            [
                'chat_id' => $id,
                'text' => $text,
                'parse_mode' => 'html'
            ]
        );
    }
}