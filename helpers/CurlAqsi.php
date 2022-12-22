<?php

namespace app\helpers;

use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

class CurlAqsi
{
    public static function get($url)
    {
        $response = self::createRequest()
            ->setUrl($url)
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders(['content-type' => 'application/json'])
            ->setMethod('GET')
            ->setCookies(self::auth())
            ->send();

        return $response;
    }

    private static function auth()
    {
        $response = self::createRequest()
            ->setUrl('https://lk.aqsi.ru/auth')
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders(['content-type' => 'application/json'])
            ->setContent(
                sprintf(
                    '{"emailOrPhone": "%s", "password": "%s"}',
                    \Yii::$app->params['aqsi']['emailOrPhone'],
                    \Yii::$app->params['aqsi']['password']
                )
            )
            ->setMethod('POST')
            ->send();

        return $response->getCookies();
    }

    private static function createRequest()
    {
        return (new Client(
            [
                'transport' => CurlTransport::class //только cURL поддерживает нужные нам параметры
            ]
        ))->createRequest()
            ->setOptions(
                [
                    //CURLOPT_PROXY => 'http://proxy.equifax.local:8090',
                    CURLOPT_SSL_VERIFYSTATUS => false,
                    CURLOPT_SSL_VERIFYPEER => false
                ]
            );
    }
}