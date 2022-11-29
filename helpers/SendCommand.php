<?php

namespace app\helpers;

class SendCommand
{
    private string $token;

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
    private function curl($url, $data)
    {
        //$data['text'] = urlencode($data['text']);
        $ch = curl_init();
        //  set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        //  number of POST vars
        curl_setopt($ch, CURLOPT_POST, count($data));
        //  POST data
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //  To display result of curl
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //  execute post
        $result = curl_exec($ch);
        //  close connection
        curl_close($ch);
        return $result;
    }

    /**
     * @throws \JsonException
     */
    public function sendMessage($id, $text): array
    {
        $params = [
            'chat_id'    => $id,
            'text'       => $text,
            'parse_mode' => 'html'
        ];

        if (is_array($text)) {
            unset($params['text']);
            $params = array_merge($params, $text);
        }
        
        return $this->send(
            'sendMessage',
            $params
        );
    }
}