<?php

namespace app\service;

use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Request;

class AqsiApi
{
    private $appId;

    private $baseUrl = 'https://api.aqsi.ru/pub/v2';

    private $url = [
        'goods'         => '/Goods/list',
        'goodsCategory' => '/GoodsCategory/list'
    ];

    public function __construct()
    {
        $this->appId = \Yii::$app->params['aqsiApp'];
    }

    public function getGoodsCategory()
    {
        return $this->get('goodsCategory');
    }

    public function getGoods()
    {
        return $this->get('goods');
    }

    /**
     * @param $url
     * @param $params
     * @param $method
     * @return mixed|void
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function get($url, $params = [], $method = 'GET')
    {
        $response = $this->createRequest()
            ->setUrl($this->getUrl($url))
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
     * @return mixed|void
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function post($url, $params = [], $method = 'POST')
    {
        $response = $this->createRequest()
            ->setUrl($this->getUrl($url))
            ->addData($params)
            ->setMethod($method)
            ->send();

        if ($response->isOk) {
            return $response->data;
        }
        print_r($response);
    }

    /**
     * @return Request
     * @throws \yii\base\InvalidConfigException
     */
    private function createRequest(): Request
    {
        return (new Client())->createRequest()->setHeaders($this->getAuthHeaders());
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