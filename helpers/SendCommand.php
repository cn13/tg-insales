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
        /*if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }*/
        return json_decode($this->curl($url, $params), true);
    }

    /**
     * @param $url
     * @param $params
     * @return bool|string
     */
    private function curl($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
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