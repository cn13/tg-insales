<?php

namespace app\helpers;

use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

class Balance
{
    private static string $url = 'https://lk.aqsi.ru/api/v1/warehouses/faaad631-46ac-43d4-8291-7d4e3543d737?page={page}&pageSize=50&sorted%5B0%5D%5Bid%5D=name&sorted%5B0%5D%5Bdesc%5D=false';

    public static function getBalance($page = 0)
    {
        $balance = [];
        while (true) {
            $url = str_replace('{page}', $page, static::$url);
            $result = self::get($url)->getData();
            if (empty($result['goods']['rows'])) {
                break;
            }
            foreach ($result['goods']['rows'] as $row) {
                $balance[] = [
                    'id' => $row['id'],
                    'price' => $row['price'],
                    'balance' => $row['warehousesGoods']['balance'],
                    'group_id' => $row['group']['id'],
                    'group_name' => $row['group']['name'],
                ];
            }
            $page++;
        }
        return $balance;
    }

    private static function get($url)
    {
        $request = (new Client(
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
        $response = $request
            ->setUrl($url)
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders(['content-type' => 'application/json'])
            ->setMethod('GET')
            ->setCookies(self::auth())
            ->send();

        return $response;
    }

    public static function auth()
    {
        $request = (new Client(
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
        $response = $request
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
}