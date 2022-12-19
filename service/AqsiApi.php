<?php

namespace app\service;

use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
use yii\httpclient\Request;

class AqsiApi
{
    private $appId;

    private $baseUrl = 'https://api.aqsi.ru/pub/v2';

    private $url = [
        'goods' => '/Goods/list',
        'goodsCategory' => '/GoodsCategory/list',
        'createClient' => '/Clients',
        'getClient' => '/Clients/',
        'getReceipts' => '/Receipts/',
    ];

    public function __construct()
    {
        $this->appId = \Yii::$app->params['aqsiApp'];
    }

    public function getGoodsCategory()
    {
        return $this->get('goodsCategory');
    }

    public function getGoods($params)
    {
        return $this->get('goods', $params);
    }

    public function getGood($id)
    {
        return $this->get($this->baseUrl . '/Goods/' . $id, [], 'GET', false);
    }

    public function createClient($params)
    {
        $this->post('createClient', $params);
    }

    public function getClient($id)
    {
        return $this->get($this->baseUrl . '/Clients/' . $id, [], 'GET', false);
    }

    public function getReceipts($params)
    {
        return $this->get('getReceipts', $params);
    }

    /**
     * @param $url
     * @param $params
     * @param $method
     * @return mixed|void
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function get($url, $params = [], $method = 'GET', $alias = true)
    {
        $response = $this->createRequest()
            ->setUrl($alias ? $this->getUrl($url) : $url)
            ->addData($params)
            ->setMethod($method)
            ->send();

        if ($response->isOk) {
            return $response->data;
        }
        print_r($response);
    }

    /**
     * @param $url
     * @param $params
     * @param $method
     * @return mixed
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function post($url, $params = [], $method = 'POST')
    {
        $response = $this->createRequest()
            ->setUrl($this->getUrl($url))
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders(['content-type' => 'application/json'])
            ->setContent(json_encode($params))
            ->setMethod($method)
            ->send();

        if ($response->isOk) {
            return $response->data;
        }
        throw new Exception(print_r($response, true) . json_encode($params));
    }

    /**
     * @return Request
     * @throws \yii\base\InvalidConfigException
     */
    private function createRequest(): Request
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
            )
            ->setHeaders($this->getAuthHeaders());
    }

    /**
     * @param string $name
     * @return string
     */
    private function getUrl(string $name): string
    {
        return $this->baseUrl . $this->url[$name] ?? '';
    }

    /**
     * @return string[]
     */
    private function getAuthHeaders(): array
    {
        return [
            'x-client-key' => 'Application ' . $this->appId
        ];
    }
}